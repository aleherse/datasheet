<?php

namespace Arkschools\DataInputSheet;

use Arkschools\DataInputSheet\Bridge\Symfony\Entity\Cell;
use Doctrine\ORM\EntityManager;

class DataInputSheetRepository
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

    public function __construct(EntityManager $em, ColumnFactory $columnFactory, $config)
    {
        $this->em            = $em;
        $this->columnFactory = $columnFactory;
        $this->config        = $config;
    }

    /**
     * @param Spine $spine
     * @param string $sheetId
     */
    public function addSpine(Spine $spine, $sheetId)
    {
        if (isset($this->config[$sheetId])) {
            $columns = [];
            foreach ($this->config[$sheetId]['columns'] as $columnTitle => $columnType) {
                $columns[$columnTitle] = $this->columnFactory->create($columnType, $columnTitle);
            }

            $views = [];
            foreach ($this->config[$sheetId]['views'] as $viewTitle => $columnNames) {
                $viewColumns = [];
                foreach ($columnNames as $title) {
                    if (isset($columns[$title])) {
                        $viewColumns[] = $columns[$title];
                    }
                }
                $view   = new View($sheetId, $viewTitle, $spine, $viewColumns);
                $viewId = $view->getId();

                $this->views[$sheetId][$viewId] = $view;
                $views[$viewId]                 = $viewTitle;
            }

            $this->sheets[$sheetId] = new Sheet(
                $spine->getHeader(),
                $views,
                $this->config[$sheetId]['config']['table']
            );
        }
    }

    /**
     * @return Sheet[]
     */
    public function findAll()
    {
        return $this->sheets;
    }

    /**
     * @param string $id
     * @return Sheet|null
     */
    public function findById($id)
    {
        return (isset($this->sheets[$id])) ? $this->sheets[$id] : null;
    }

    /**
     * @param string $sheetId
     * @param string $viewId
     * @return View|null
     */
    public function findViewBy($sheetId, $viewId)
    {
        if (!isset($this->views[$sheetId][$viewId])) {
            return null;
        }

        $view = $this->views[$sheetId][$viewId];
        $this->updateCustomTableName($sheetId);

        $cells = $this->em
            ->createQueryBuilder()
            ->select('c')
            ->from(Cell::class, 'c')
            ->where('c.sheet = :sheetId')
            ->andWhere('c.column IN (:columns)')
            ->setParameters([
                'sheetId' => $sheetId,
                'columns' => array_keys($view->getColumns())
            ])
            ->getQuery()
            ->execute();

        return $view->loadCells($cells);
    }

    public function save(View $view, $data)
    {
        $this->updateCustomTableName($view->getSheetId());

        $columns = $view->getColumns();
        foreach ($data as $columnId => $spine) {
            if (isset($columns[$columnId])) {
                foreach ($spine as $spineId => $content) {
                    $content = $columns[$columnId]->castCellContent($content);

                    if ($view->contentChanged($columnId, $spineId, $content)) {
                        $cell = $view->getCell($columnId, $spineId);
                        if (null !== $content) {
                            $this->em->persist($cell->setContent($content));
                        } else {
                            $this->em->remove($cell);
                        }
                    }
                }
            }
        }

        $this->em->flush();
    }

    private function updateCustomTableName($sheetId)
    {
        $tableName = $this->findById($sheetId)->getTableName();

        if (null !== $tableName) {
            $this->em
                ->getClassMetadata(Cell::class)
                ->setPrimaryTable(['name' => $tableName]);
        }
    }
}
