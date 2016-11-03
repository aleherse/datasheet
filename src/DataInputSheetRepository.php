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

    public function __construct(EntityManager $em, $config)
    {
        $this->config = $config;
        $this->em     = $em;
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
                $columns[$columnTitle] = Column::$columnType($columnTitle);
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

            $this->sheets[$sheetId] = new Sheet($sheetId, $views);
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
        foreach ($data as $columnId => $spine) {
            foreach ($spine as $spineId => $content) {
                $content = trim($content);
                $content = (empty($content)) ? null : $content;

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

        $this->em->flush();
    }
}
