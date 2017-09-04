<?php

namespace Arkschools\DataInputSheets;

use Arkschools\DataInputSheets\Security\SheetAccessVoter;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DataInputSheetsRepository
{
    /**
     * @var Sheet[]
     */
    private $sheets;

    /**
     * @var View[][]
     */
    private $views;

    /**
     * @var array
     */
    private $config;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ColumnFactory
     */
    private $columnFactory;

    /**
     * @var SelectorFactory
     */
    private $selectorFactory;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorization;

    public function __construct(
        ManagerRegistry $registry,
        ColumnFactory $columnFactory,
        SelectorFactory $selectorFactory,
        AuthorizationCheckerInterface $authorizationChecker,
        array $config,
        string $entityManagerName = null
    )
    {
        $this->em              = $registry->getManager($entityManagerName);
        $this->columnFactory   = $columnFactory;
        $this->selectorFactory = $selectorFactory;
        $this->config          = $config;
        $this->authorization   = $authorizationChecker;
    }

    public function addSpine(Spine $spine, string $sheetId): void
    {
        if (!isset($this->config[$sheetId])) {
            return;
        }

        $columns = [];

        foreach ($this->config[$sheetId]['columns'] as $columnTitle => $columnConfig) {
            $columns[$columnTitle] = $this->columnFactory->create($columnConfig, $columnTitle);
        }

        $selector = null;
        if (null !== $this->config[$sheetId]['selector']) {
            $selector = $this->selectorFactory->create($this->config[$sheetId]['selector']);
        }

        $views = [];

        foreach ($this->config[$sheetId]['views'] as $viewTitle => $viewColumn) {
            $viewColumns     = [];
            $hiddenColumnIds = [];

            foreach ($viewColumn['columns'] as $viewColumnData) {
                if (!isset($columns[$viewColumnData['column']])) {
                    throw new \LogicException(
                        sprintf(
                            'The view \'%s\' is not properly configured. It contains the column \'%s\' that is not defined on the sheet \'%s\'',
                            $viewTitle,
                            $viewColumnData['column'],
                            $sheetId
                        )
                    );
                }

                $column        = $columns[$viewColumnData['column']];
                $viewColumns[] = $column;
                if ($viewColumnData['hide']) {
                    $hiddenColumnIds[$column->getId()] = true;
                }
            }

            $view = new View(
                $sheetId,
                $viewTitle,
                $spine,
                $viewColumn['filters'],
                $viewColumns,
                $hiddenColumnIds,
                $selector
            );

            $viewId                         = $view->getId();
            $this->views[$sheetId][$viewId] = $view;
            $views[$viewId]                 = $viewTitle;
        }

        $this->sheets[$sheetId] = new Sheet($sheetId, $spine->getHeader(), $views, $this->config[$sheetId]['users']);
    }

    /**
     * @return Sheet[]
     */
    public function findAll(): array
    {
        $sheets = [];

        foreach ($this->sheets as $sheet) {
            if ($this->isGranted($sheet)) {
                $sheets[] = $sheet;
            }
        }

        return $sheets;
    }

    private function isGranted(Sheet $sheet)
    {
        return $this->authorization->isGranted(SheetAccessVoter::ACCESS, $sheet);
    }

    public function findById(string $id): ?Sheet
    {
        return (isset($this->sheets[$id]) && $this->isGranted($this->sheets[$id]))
            ? $this->sheets[$id]
            : null;
    }

    public function findViewBy(string $sheetId, string $viewId): ?View
    {
        if (!isset($this->sheets[$sheetId])) {
            return null;
        }
        if (!$this->isGranted($this->sheets[$sheetId])) {
            return null;
        }

        if (!isset($this->views[$sheetId][$viewId])) {
            return null;
        }

        $view = $this->views[$sheetId][$viewId];

        return $view->loadContent($this->em);
    }

    public function save(View $view, array $data): void
    {
        $view->persist($this->em, $data);

        $this->em->flush();
    }
}
