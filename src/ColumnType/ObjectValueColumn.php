<?php

namespace Arkschools\DataInputSheets\ColumnType;

class ObjectValueColumn extends AbstractColumn
{
    /**
     * @var string
     */
    private $methodName;

    public function __construct(string $methodName)
    {
        parent::__construct(
            'DataInputSheetsBundle:extension:data_input_sheets_object_value_cell.html.twig',
            self::NONE
        );

        $this->methodName = $methodName;
    }

    public function isValueColumn(): bool
    {
        return true;
    }

    public function getValue($object): string
    {
        return call_user_func([$object, $this->methodName]);
    }
}
