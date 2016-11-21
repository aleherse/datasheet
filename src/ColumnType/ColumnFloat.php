<?php

namespace Arkschools\DataInputSheet\ColumnType;

use Arkschools\DataInputSheet\Bridge\Symfony\Entity\Cell;
use Arkschools\DataInputSheet\Column;

class ColumnFloat extends Column
{
    /**
     * @return int
     */
    public function getType()
    {
        return self::FLOAT;
    }

    /**
     * @return int
     */
    public function getDBType()
    {
        return self::FLOAT;
    }

    /**
     * @param string $content
     * @return float
     */
    public function castCellContent($content)
    {
        $content = parent::castCellContent($content);

        return (null !== $content) ? floatval($content) : null;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return 'DataInputSheetBundle:extension:data_input_sheet_cell_input_text.html.twig';
    }
}
