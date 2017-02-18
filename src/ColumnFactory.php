<?php

namespace Arkschools\DataInputSheet;

use Arkschools\DataInputSheet\ColumnType\ColumnBase;
use Arkschools\DataInputSheet\ColumnType\ColumnFloat;
use Arkschools\DataInputSheet\ColumnType\ColumnGender;
use Arkschools\DataInputSheet\ColumnType\ColumnInteger;
use Arkschools\DataInputSheet\ColumnType\ColumnString;
use Arkschools\DataInputSheet\ColumnType\ColumnText;
use Arkschools\DataInputSheet\ColumnType\ColumnYesNo;

class ColumnFactory
{
    private $types;

    public function __construct(array $extraTypes = [])
    {
        $this->types = [
            'integer' => new ColumnInteger(),
            'float'   => new ColumnFloat(),
            'string'  => new ColumnString(),
            'text'    => new ColumnText(),
            'gender'  => new ColumnGender(),
            'yes/no'  => new ColumnYesNo()
        ];

        foreach ($extraTypes as $type => $class) {
            if (class_exists($class)) {
                $this->types[$type] = new $class;
            }
        }
    }

    public function addColumnType(ColumnBase $columnType, $type)
    {
        $this->types[$type] = $columnType;
    }

    public function create(array $config, $title)
    {
        $field  = (isset($config['field'])) ? $config['field'] : null;
        $option = (isset($config['option'])) ? $config['option'] : null;

        if (isset($this->types[$config['type']])) {
            return new Column($this->types[$config['type']], $title, $field, $option);
        }

        return new Column($this->types['string'], $title, $field, $option);
    }
}
