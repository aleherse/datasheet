<?php

namespace Arkschools\DataInputSheets\ColumnType;

class YesNoColumn extends AbstractColumn
{
    public function __construct()
    {
        parent::__construct(
            'DataInputSheetsBundle:extension:data_input_sheets_cell_yes_no.html.twig',
            self::STRING
        );
    }
}
