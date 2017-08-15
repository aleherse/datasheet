<?php

namespace Arkschools\DataInputSheets\ColumnType;

abstract class AbstractColumn
{
    const NONE = -1;
    const BOOL = 0;
    const INTEGER = 1;
    const FLOAT = 2;
    const STRING = 3;
    const TEXT = 4;
    const DATETIME = 5;
    //
    const INPUT_SIZE = 60;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var integer
     */
    protected $dbType;

    public function __construct($template, $dbType)
    {
        $this->template = $template;
        $this->dbType   = $dbType;
    }

    public function render(\Twig_Environment $twig, $columnId, $spineId, $content, $option = null, bool $readOnly = false)
    {
        return $twig->render(
            $this->template,
            [
                'columnId' => $columnId,
                'spineId'  => $spineId,
                'content'  => $content,
                'readOnly' => $readOnly,
                'size'     => static::INPUT_SIZE
            ]
        );
    }

    public function getDbType()
    {
        return $this->dbType;
    }

    public function castCellContent(string $content, $option = null)
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

    public function isStored(): bool
    {
        return true;
    }

    public function getValue($object): string
    {
        return '';
    }
}
