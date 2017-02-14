<?php

namespace Arkschools\DataInputSheet\ColumnType;

use Arkschools\DataInputSheet\Column;

class ColumnYesNo extends Column
{
    /**
     * @return int
     */
    public function getType()
    {
        return self::STRING;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return 'DataInputSheetBundle:extension:data_input_sheet_cell_yes_no.html.twig';
    }

    /**
     * @return int
     */
    public function getDBType()
    {
        return self::STRING;
    }
}
