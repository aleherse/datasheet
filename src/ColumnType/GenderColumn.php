<?php

namespace Arkschools\DataInputSheets\ColumnType;

class GenderColumn extends AbstractColumn
{
    public function __construct()
    {
        parent::__construct(
            'DataInputSheetsBundle:extension:data_input_sheets_cell_gender.html.twig',
            self::STRING
        );
    }
}
