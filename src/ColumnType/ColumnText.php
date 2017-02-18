<?php

namespace Arkschools\DataInputSheet\ColumnType;

class ColumnText extends ColumnBase
{
    public function __construct()
    {
        parent::__construct(
            'DataInputSheetBundle:extension:data_input_sheet_cell_textarea.html.twig',
            self::STRING
        );
    }
}
