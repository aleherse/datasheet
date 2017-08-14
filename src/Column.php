<?php

namespace Arkschools\DataInputSheets;

use Arkschools\DataInputSheets\Bridge\Symfony\Entity\Cell;
use Arkschools\DataInputSheets\ColumnType\AbstractColumn;

class Column
{
    const BOOL = 0;
    const INTEGER = 1;
    const FLOAT = 2;
    const STRING = 3;
    const TEXT = 4;
    const DATETIME = 5;

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
     * @var string|string[]
     */
    private $option;

    /**
     * @var bool
     */
    private $readOnly;

    public function __construct(AbstractColumn $columnType, string $title, string $field = null, $option = null, bool $readOnly = false)
    {
        $this->id         = \slugifier\slugify($title);
        $this->columnType = $columnType;
        $this->title      = $title;
        $this->field      = $field;
        $this->option     = $option;
        $this->readOnly   = $readOnly;
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

    public function castCellContent($content)
    {
        return $this->columnType->castCellContent($content, $this->option);
    }

    public function createCell(string $sheetId, string $spineId, $content = null): Cell
    {
        return new Cell($sheetId, $this->id, $spineId, $this->columnType->getDbType(), $this->castCellContent($content));
    }

    public function render(\Twig_Environment $twig, $columnId, $spineId, $content): string
    {
        return $this->columnType->render($twig, $columnId, $spineId, $content, $this->option, $this->readOnly);
    }

    public function isValueColumn(): bool
    {
        return $this->columnType->isValueColumn();
    }

    public function isStored(): bool
    {
        return $this->columnType->isStored();
    }

    public function getValue($object): string
    {
        return $this->columnType->getValue($object);
    }

    public function isReadOnly(): bool
    {
        return $this->readOnly;
    }
}
