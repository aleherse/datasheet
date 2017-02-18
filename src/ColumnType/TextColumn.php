<?php

namespace Arkschools\DataInputSheets\ColumnType;

class TextColumn extends AbstractColumn
{
    public function __construct()
    {
        parent::__construct(
            'DataInputSheetsBundle:extension:data_input_sheets_textarea_cell.html.twig',
            self::STRING
        );
    }
}
