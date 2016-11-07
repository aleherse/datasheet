<?php

namespace Arkschools\DataInputSheet;

use Arkschools\DataInputSheet\Bridge\Symfony\Entity\Cell;

abstract class Column
{
    const INTEGER = 0;
    const FLOAT = 1;
    const STRING = 3;
    const TEXT = 4;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $type;

    public function __construct($title)
    {
        $this->id = \slugifier\slugify($title);
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public abstract function getType();

    /**
     * @return string
     */
    public abstract function getTemplate();

    /**
     * @return int
     */
    public abstract function getCellType();

    /**
     * @param string $content
     * @return string|null
     */
    public function castCellContent($content)
    {
        $content = trim($content);
        if (empty($content) && !is_numeric($content)) {
            return null;
        }

        return $content;
    }

    /**
     * @param string $sheetId
     * @param string $spineId
     * @param mixed $content
     * @return Cell
     */
    public function createCell($sheetId, $spineId, $content = null)
    {
        return new Cell($sheetId, $this->id, $spineId, $this->getCellType(), $this->castCellContent($content));
    }
}
