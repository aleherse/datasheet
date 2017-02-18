<?php

namespace Arkschools\DataInputSheets;

use Arkschools\DataInputSheets\Bridge\Symfony\Entity\Cell;
use Arkschools\DataInputSheets\ColumnType\AbstractColumn;

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
     * @var AbstractColumn
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

    public function __construct(AbstractColumn $columnType, string $title, ?string $field = null, ?string $option = null)
    {
        $this->id         = \slugifier\slugify($title);
        $this->columnType = $columnType;
        $this->title      = $title;
        $this->field      = $field;
        $this->option     = $option;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getColumnType(): AbstractColumn
    {
        return $this->columnType;
    }

    public function castCellContent($content): ?string
    {
        return $this->columnType->castCellContent($content, $this->option);
    }

    public function createCell(string $sheetId, string $spineId, $content = null): Cell
    {
        return new Cell($sheetId, $this->id, $spineId, $this->columnType->getDbType(), $this->castCellContent($content));
    }

    public function render(\Twig_Environment $twig, $columnId, $spineId, $content): string
    {
        return $this->columnType->render($twig, $columnId, $spineId, $content, $this->option);
    }

    public function isValueColumn(): bool
    {
        return $this->columnType->isValueColumn();
    }


    public function getValue($object): string
    {
        return $this->columnType->getValue($object);
    }
}
