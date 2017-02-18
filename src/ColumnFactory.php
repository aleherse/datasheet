<?php

namespace Arkschools\DataInputSheets;

use Arkschools\DataInputSheets\ColumnType\AbstractColumn;
use Arkschools\DataInputSheets\ColumnType\FloatColumn;
use Arkschools\DataInputSheets\ColumnType\GenderColumn;
use Arkschools\DataInputSheets\ColumnType\IntegerColumn;
use Arkschools\DataInputSheets\ColumnType\ObjectValueColumn;
use Arkschools\DataInputSheets\ColumnType\StringColumn;
use Arkschools\DataInputSheets\ColumnType\TextColumn;
use Arkschools\DataInputSheets\ColumnType\YesNoColumn;

class ColumnFactory
{
    /**
     * @var AbstractColumn[]
     */
    private $types;

    public function __construct(array $extraTypes = [])
    {
        $this->types = [
            'integer' => new IntegerColumn(),
            'float'   => new FloatColumn(),
            'string'  => new StringColumn(),
            'text'    => new TextColumn(),
            'gender'  => new GenderColumn(),
            'yes/no'  => new YesNoColumn(),
        ];

        foreach ($extraTypes as $type => $class) {
            if (class_exists($class)) {
                $this->types[$type] = new $class;
            }
        }
    }

    public function addColumnType(AbstractColumn $columnType, string $type)
    {
        $this->types[$type] = $columnType;
    }

    public function create(array $config, string $title)
    {
        $field  = $config['field'] ?? null;
        $option = $config['option'] ?? null;

        if (isset($this->types[$config['type']])) {
            return new Column($this->types[$config['type']], $title, $field, $option);
        }

        if ('@' === substr($config['type'], 0, 1)) {
            return new Column(new ObjectValueColumn(substr($config['type'], 1)), $title, $field, $option);
        }

        return new Column($this->types['string'], $title, $field, $option);
    }
}
