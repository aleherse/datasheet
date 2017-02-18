<?php

namespace Arkschools\DataInputSheet\ColumnType;

abstract class ColumnBase
{
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
        $this->dbType = $dbType;
    }

    public function render(\Twig_Environment $twig, $columnId, $spineId, $content, $option = null)
    {
        return $twig->render($this->template, [
            'columnId' => $columnId,
            'spineId'  => $spineId,
            'content'  => $content
        ]);
    }

    /**
     * @return int
     */
    public function getDbType()
    {
        return $this->dbType;
    }

    /**
     * @param string $content
     * @param string $option
     * @return null|string
     */
    public function castCellContent($content, $option = null)
    {
        $content = trim($content);
        if (empty($content) && !is_numeric($content)) {
            return null;
        }

        return $content;
    }
}
