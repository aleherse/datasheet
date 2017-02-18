<?php

namespace Arkschools\DataInputSheet\ColumnType;

class ColumnFloat extends ColumnBase
{
    public function __construct()
    {
        parent::__construct(
            'DataInputSheetBundle:extension:data_input_sheet_cell_input_text.html.twig',
            self::FLOAT
        );
    }

    /**
     * @param string $content
     * @param string $option
     * @return float
     */
    public function castCellContent($content, $option = null)
    {
        $content = parent::castCellContent($content, $option);

        return (null !== $content) ? floatval($content) : null;
    }
}
