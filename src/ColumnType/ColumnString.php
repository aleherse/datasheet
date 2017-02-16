<?php

namespace Arkschools\DataInputSheet\ColumnType;

class ColumnString extends ColumnBase
{
    public function __construct()
    {
        parent::__construct(
            'DataInputSheetBundle:extension:data_input_sheet_cell_input_text.html.twig',
            self::STRING
        );
    }
}
