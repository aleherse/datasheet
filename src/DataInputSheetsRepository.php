<?php

namespace Arkschools\DataInputSheets;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

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

    public function __construct(ManagerRegistry $registry, ColumnFactory $columnFactory, $config, $entityManagerName = null)
    {
        $this->em            = $registry->getManager($entityManagerName);
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
            foreach ($this->config[$sheetId]['columns'] as $columnTitle => $columnConfig) {
                $columns[$columnTitle] = $this->columnFactory->create($columnConfig, $columnTitle);
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

            $this->sheets[$sheetId] = new Sheet($sheetId, $spine->getHeader(), $views);
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

        return $view->loadContent($this->em);
    }

    public function save(View $view, $data)
    {
        $view->persist($this->em, $data);

        $this->em->flush();
    }
}
