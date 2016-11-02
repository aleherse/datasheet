<?php

namespace Arkschools\DataInputSheet;

class View
{
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
     * @var array
     */
    private $columns;

    public function __construct($title, Spine $spine, array $columns)
    {
        $this->id = \slugifier\slugify($title);
        $this->title = $title;
        $this->spine = $spine;

        $this->columns = [];
        /** @var Column $column */
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
     * @return array
     */
    public function getColumns()
    {
        return array_values($this->columns);
    }

    public function getColumn($columnId)
    {
        return (isset($this->columns[$columnId])) ? $this->columns[$columnId] : null;
    }

    /**
     * @param string $spineRow
     * @param string $columnId
     *
     * @return array
     */
    public function getCell($spineRow, $columnId)
    {
        return [
            'id'     => 'lexus-is-200-brand',
            'value'  => null,
            'custom' => [],
        ];
    }
}
