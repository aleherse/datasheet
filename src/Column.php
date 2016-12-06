<?php

namespace Arkschools\DataInputSheet;

use Arkschools\DataInputSheet\Bridge\Symfony\Entity\Cell;
use Arkschools\DataInputSheet\Bridge\Symfony\Entity\CustomCell;

abstract class Column
{
    const INTEGER = 0;
    const FLOAT = 1;
    const STRING = 2;
    const TEXT = 3;

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

    /**
     * @var string
     */
    private $field;

    public function __construct($title, $field = null)
    {
        $this->id    = \slugifier\slugify($title);
        $this->title = $title;
        $this->field = $field;
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
     * @return string
     */
    public function getField()
    {
        return $this->field;
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
    public abstract function getDBType();

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
     * @param bool $isCustomCell
     * @return Cell|CustomCell
     */
    public function createCell($sheetId, $spineId, $content = null, $isCustomCell = false)
    {
        if ($isCustomCell) {
            return new CustomCell($this->id, $spineId, $this->getDBType(), $this->castCellContent($content));
        } else {
            return new Cell($sheetId, $this->id, $spineId, $this->getDBType(), $this->castCellContent($content));
        }
    }
}
