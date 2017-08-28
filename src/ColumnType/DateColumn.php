<?php

namespace Arkschools\DataInputSheets\ColumnType;

class DateColumn extends AbstractColumn
{
    const DEFAULT_DATE_FORMAT = 'd/m/Y H:i';
    const DEFAULT_DATE_VIEW_FORMAT = 'DD/MM/YYYY HH:MM';

    public function __construct()
    {
        parent::__construct(
            'DataInputSheetsBundle:extension:data_input_sheets_input_date_cell.html.twig',
            self::DATETIME
        );
    }

    public function castCellContent($content, $option = null): ?\DateTime
    {
        $content = \DateTime::createFromFormat(self::dateFormat($option), parent::castCellContent($content, $option));

        return false !== $content ? $content : null;
    }

    private static function dateFormat(array $option = null, $forDb = true)
    {
        if ($forDb) {
            return $option[0] ?? self::DEFAULT_DATE_FORMAT;
        }

        return $option[1] ?? self::DEFAULT_DATE_VIEW_FORMAT;
    }

    public function render(\Twig_Environment $twig, $columnId, $spineId, $content, $option = null, bool $readOnly = false)
    {
        return $twig->render(
            $this->template,
            [
                'columnId' => $columnId,
                'spineId'  => $spineId,
                'content'  => $content instanceof \DateTime ? $content->format(self::dateFormat($option)) : $content,
                'format'   => self::dateFormat($option, false),
                'readOnly' => $readOnly
            ]
        );
    }
}
