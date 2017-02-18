<?php

namespace Arkschools\DataInputSheets\ColumnType;

class YesNoColumn extends AbstractColumn
{
    public function __construct()
    {
        parent::__construct(
            'DataInputSheetsBundle:extension:data_input_sheets_yes_no_cell.html.twig',
            self::STRING
        );
    }
}
