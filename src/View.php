<?php

namespace Arkschools\DataInputSheet;

use Arkschools\DataInputSheet\Bridge\Symfony\Entity\Cell;

class View
{
    /**
     * @var string
     */
    private $sheetId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $id;

    /**
     * @var Spine
     */
    private $spine;

    /**
     * @var Column[]
     */
    private $columns;

    /**
     * @var Cell[][]
     */
    private $cells;

    public function __construct($sheetId, $title, Spine $spine, array $columns)
    {
        $this->sheetId = $sheetId;
        $this->id    = \slugifier\slugify($title);
        $this->title = $title;
        $this->spine = $spine;

        /** @var Column $column */
        $this->columns = [];
        foreach ($columns as $column) {
            $this->columns[$column->getId()] = $column;
        }
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSheetId()
    {
        return $this->sheetId;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSpineHeader()
    {
        return $this->spine->getHeader();
    }

    /**
     * @return \string[]
     */
    public function getSpine()
    {
        return $this->spine->getSpine();
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param string $columnId
     * @return Column
     */
    public function getColumn($columnId)
    {
        return (isset($this->columns[$columnId])) ? $this->columns[$columnId] : null;
    }

    /**
     * @param string $columnId
     * @param string $spineId
     * @return Cell
     */
    public function getCell($columnId, $spineId)
    {
        if (!isset($this->cells[$columnId][$spineId])) {
            $this->cells[$columnId][$spineId] = new Cell($this->sheetId, $columnId, $spineId, null);
        }

        return $this->cells[$columnId][$spineId];
    }

    /**
     * @param string $columnId
     * @param string $spineId
     * @param string $content
     * @return bool
     */
    public function contentChanged($columnId, $spineId, $content)
    {
        return $this->getCell($columnId, $spineId)->getContent() !== $content;
    }

    /**
     * @param Cell[] $cells
     * @return $this
     */
    public function loadCells(array $cells)
    {
        $this->cells = [];
        foreach ($cells as $cell) {
            $this->cells[$cell->getColumn()][$cell->getSpine()] = $cell;
        }

        return $this;
    }
}
