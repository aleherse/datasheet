<?php

namespace Arkschools\DataInputSheet\ColumnType;

use Arkschools\DataInputSheet\Bridge\Symfony\Entity\Cell;
use Arkschools\DataInputSheet\Column;

class ColumnText extends Column
{
    /**
     * @return int
     */
    public function getType()
    {
        return self::TEXT;
    }

    /**
     * @return int
     */
    public function getDBType()
    {
        return self::TEXT;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return 'DataInputSheetBundle:extension:data_input_sheet_cell_textarea.html.twig';
    }
}
