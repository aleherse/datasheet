<?php

namespace Arkschools\DataInputSheets\ColumnType;

class IntegerColumn extends AbstractColumn
{
    public function __construct()
    {
        parent::__construct(
            'DataInputSheetsBundle:extension:data_input_sheets_input_text_tell.html.twig',
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
