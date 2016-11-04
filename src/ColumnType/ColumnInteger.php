<?php

namespace Arkschools\DataInputSheet\ColumnType;

use Arkschools\DataInputSheet\Bridge\Symfony\Entity\Cell;
use Arkschools\DataInputSheet\Column;

class ColumnInteger extends Column
{
    /**
     * @return int
     */
    public function getType()
    {
        return Column::INTEGER;
    }

    /**
     * @return int
     */
    public function getCellType()
    {
        return Cell::TYPE_INTEGER;
    }

    /**
     * @param string $content
     * @return integer
     */
    public function castCellContent($content)
    {
        $content = parent::castCellContent($content);

        return (null !== $content) ? intval($content) : null;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return 'DataInputSheetBundle:extension:data_input_sheet_cell_input_text.html.twig';
    }
}
