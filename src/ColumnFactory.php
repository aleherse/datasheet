<?php

namespace Arkschools\DataInputSheet;

use Arkschools\DataInputSheet\ColumnType\ColumnFloat;
use Arkschools\DataInputSheet\ColumnType\ColumnGender;
use Arkschools\DataInputSheet\ColumnType\ColumnInteger;
use Arkschools\DataInputSheet\ColumnType\ColumnString;
use Arkschools\DataInputSheet\ColumnType\ColumnText;
use Arkschools\DataInputSheet\ColumnType\ColumnYesNo;

class ColumnFactory
{
    private static $types = [
        'integer' => ColumnInteger::class,
        'float'   => ColumnFloat::class,
        'string'  => ColumnString::class,
        'text'    => ColumnText::class,
        'gender'  => ColumnGender::class,
        'yes/no'  => ColumnYesNo::class
    ];

    public function __construct(array $extraTypes = [])
    {
        foreach ($extraTypes as $type => $class) {
            if (class_exists($class)) {
                self::$types[$type] = $class;
            }
        }
    }

    public function create(array $config, $title)
    {
        $field = (isset($config['field'])) ? $config['field'] : null;

        if (isset(self::$types[$config['type']])) {
            return new self::$types[$config['type']]($title, $field);
        }

        return new self::$types['string']($title, $field);
    }
}
