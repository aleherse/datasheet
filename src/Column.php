<?php

namespace Arkschools\DataInputSheet;

use Arkschools\DataInputSheet\Bridge\Symfony\Entity\Cell;
use Arkschools\DataInputSheet\ColumnType\ColumnBase;

class Column
{
    const INTEGER = 0;
    const FLOAT = 1;
    const STRING = 2;
    const TEXT = 3;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var ColumnBase
     */
    protected $columnType;

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $option;

    public function __construct(ColumnBase $columnType, $title, $field = null, $option = null)
    {
        $this->id         = \slugifier\slugify($title);
        $this->columnType = $columnType;
        $this->title      = $title;
        $this->field      = $field;
        $this->option     = $option;
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
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return ColumnBase
     */
    public function getColumnType()
    {
        return $this->columnType;
    }

    /**
     * @param string $content
     * @return string|null
     */
    public function castCellContent($content)
    {
        return $this->columnType->castCellContent($content, $this->option);
    }

    /**
     * @param string $sheetId
     * @param string $spineId
     * @param mixed $content
     * @return Cell
     */
    public function createCell($sheetId, $spineId, $content = null)
    {
        return new Cell($sheetId, $this->id, $spineId, $this->columnType->getDbType(), $this->castCellContent($content));
    }

    public function render(\Twig_Environment $twig, $columnId, $spineId, $content)
    {
        return $this->columnType->render($twig, $columnId, $spineId, $content, $this->option);
    }
}
