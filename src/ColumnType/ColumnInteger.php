<?php

namespace Arkschools\DataInputSheet\ColumnType;

class ColumnInteger extends ColumnBase
{
    public function __construct()
    {
        parent::__construct(
            'DataInputSheetBundle:extension:data_input_sheet_cell_input_text.html.twig',
            self::INTEGER
        );
    }

    /**
     * @param string $content
     * @param string $option
     * @return int
     */
    public function castCellContent($content, $option = null)
    {
        $content = parent::castCellContent($content, $option);

        return (null !== $content) ? intval($content) : null;
    }
}
