<?php

namespace Arkschools\DataInputSheet\ColumnType;

use Arkschools\DataInputSheet\Bridge\Symfony\Entity\Cell;
use Arkschools\DataInputSheet\Column;

class ColumnString extends Column
{
    /**
     * @return int
     */
    public function getType()
    {
        return Column::STRING;
    }

    /**
     * @return int
     */
    public function getCellType()
    {
        return Cell::TYPE_STRING;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return 'DataInputSheetBundle:extension:data_input_sheet_cell_input_text.html.twig';
    }
}
