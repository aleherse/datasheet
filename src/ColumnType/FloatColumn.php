<?php

namespace Arkschools\DataInputSheets\ColumnType;

class FloatColumn extends AbstractColumn
{
    const INPUT_SIZE = 15;

    public function __construct()
    {
        parent::__construct(
            'DataInputSheetsBundle:extension:data_input_sheets_input_text_cell.html.twig',
            self::FLOAT
        );
    }

    public function castCellContent($content, $option = null): ?float
    {
        $content = parent::castCellContent($content, $option);

        return null !== $content ? floatval($content) : null;
    }
}
