<?php

namespace Arkschools\DataInputSheet;

use Arkschools\DataInputSheet\ColumnType\ColumnFloat;
use Arkschools\DataInputSheet\ColumnType\ColumnInteger;
use Arkschools\DataInputSheet\ColumnType\ColumnString;
use Arkschools\DataInputSheet\ColumnType\ColumnText;

class ColumnFactory
{
    private static $types = [
        'integer' => ColumnInteger::class,
        'float'   => ColumnFloat::class,
        'string'  => ColumnString::class,
        'text'    => ColumnText::class
    ];

    public function __construct(array $extraTypes = [])
    {
        foreach ($extraTypes as $type => $class) {
            if (class_exists($class)) {
                self::$types[$type] = $class;
            }
        }
    }

    public function create($type, $title)
    {
        if (isset(self::$types[$type])) {
            return new self::$types[$type]($title);
        }

        return new self::$types['text']($title);
    }
}
