<?php

namespace Arkschools\DataInputSheets\ColumnType;

abstract class AbstractColumn
{
    const NONE = -1;
    const INTEGER = 0;
    const FLOAT = 1;
    const STRING = 2;
    const TEXT = 3;

    /**
     * @var string
     */
    private $template;

    /**
     * @var integer
     */
    private $dbType;

    public function __construct($template, $dbType)
    {
        $this->template = $template;
        $this->dbType   = $dbType;
    }

    public function render(\Twig_Environment $twig, $columnId, $spineId, $content, $option = null)
    {
        return $twig->render(
            $this->template,
            [
                'columnId' => $columnId,
                'spineId'  => $spineId,
                'content'  => $content,
            ]
        );
    }

    public function getDbType()
    {
        return $this->dbType;
    }

    public function castCellContent(string $content, ?string $option = null)
    {
        $content = trim($content);

        if (empty($content) && !is_numeric($content)) {
            return null;
        }

        return $content;
    }

    public function isValueColumn(): bool
    {
        return false;
    }

    public function getValue($object): string
    {
        return '';
    }
}
