<?php

namespace Arkschools\DataInputSheet\ColumnType;

class ColumnGender extends ColumnBase
{
    public function __construct()
    {
        parent::__construct(
            'DataInputSheetBundle:extension:data_input_sheet_cell_gender.html.twig',
            self::STRING
        );
    }
}
