<?php

namespace Arkschools\DataInputSheets\ColumnType;

class ObjectValueColumn extends AbstractColumn
{
    /**
     * @var string
     */
    private $methodName;

    /**
     * @var array
     */
    private $arguments;

    public function __construct(string $methodName, ?array $arguments)
    {
        parent::__construct(
            'DataInputSheetsBundle:extension:data_input_sheets_object_value_cell.html.twig',
            self::NONE
        );

        $this->methodName = $methodName;
        $this->arguments  = $arguments;
    }

    public function castCellContent($content, $option = null)
    {
        return null;
    }

    public function isValueColumn(): bool
    {
        return true;
    }

    public function isStored(): bool
    {
        return false;
    }

    public function getValue($object): string
    {
        return strval(call_user_func_array([$object, $this->methodName], $this->arguments ?? []));
    }
}
