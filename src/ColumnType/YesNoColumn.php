<?php

namespace Arkschools\DataInputSheets\ColumnType;

class YesNoColumn extends AbstractColumn
{
    public function __construct()
    {
        parent::__construct(
            'DataInputSheetsBundle:extension:data_input_sheets_yes_no_cell.html.twig',
            self::BOOL
        );
    }

    public function castCellContent($content, $option = null): ?bool
    {
        $content = parent::castCellContent($content);

        if (null === $content) {
            return null;
        }

        $content = strtolower($content);

        if ('y' === $content || 'yes' === $content) {
            return true;
        }

        if ('n' === $content || 'no' === $content) {
            return false;
        }

        return null;
    }
}
