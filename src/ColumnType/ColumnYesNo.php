<?php

namespace Arkschools\DataInputSheet\ColumnType;

class ColumnYesNo extends ColumnBase
{
    public function __construct()
    {
        parent::__construct(
            'DataInputSheetBundle:extension:data_input_sheet_cell_yes_no.html.twig',
            self::STRING
        );
    }
}
