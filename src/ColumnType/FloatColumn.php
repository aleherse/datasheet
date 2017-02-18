<?php

namespace Arkschools\DataInputSheets\ColumnType;

class FloatColumn extends AbstractColumn
{
    public function __construct()
    {
        parent::__construct(
            'DataInputSheetsBundle:extension:data_input_sheets_input_text_cell.html.twig',
            self::FLOAT
        );
    }

    public function castCellContent(string $content, ?string $option = null)
    {
        $content = parent::castCellContent($content, $option);

        return null !== $content ? floatval($content) : null;
    }
}
