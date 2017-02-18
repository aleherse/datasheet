<?php

namespace Arkschools\DataInputSheets\ColumnType;

class StringColumn extends AbstractColumn
{
    public function __construct()
    {
        parent::__construct(
            'DataInputSheetsBundle:extension:data_input_sheets_cell_input_text.html.twig',
            self::STRING
        );
    }
}
