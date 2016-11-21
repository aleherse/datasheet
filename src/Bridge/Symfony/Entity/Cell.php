<?php

namespace Arkschools\DataInputSheet\Bridge\Symfony\Entity;

use Arkschools\DataInputSheet\Column;

class Cell
{
    private static $types = [
        Column::INTEGER => 'contentInteger',
        Column::FLOAT   => 'contentFloat',
        Column::STRING  => 'contentString',
        Column::TEXT    => 'contentText'
    ];

    /**
     * @var string
     */
    private $sheet;

    /**
     * @var string
     */
    private $column;

    /**
     * @var string
     */
    private $spine;

    /**
     * @var string
     */
    private $type;

    /**
     * @var integer
     */
    private $contentInteger;

    /**
     * @var float
     */
    private $contentFloat;

    /**
     * @var string
     */
    private $contentString;

    /**
     * @var string
     */
    private $contentText;

    /**
     * @param string $sheet
     * @param string $column
     * @param string $spine
     * @param int    $type
     * @param string $content
     */
    public function __construct($sheet, $column, $spine, $type, $content)
    {
        $this->sheet  = $sheet;
        $this->column = $column;
        $this->spine  = $spine;
        $this->type   = $type;

        $this->setContent($content);
    }

    /**
     * @return string
     */
    public function getSheet()
    {
        return $this->sheet;
    }

    /**
     * @param string $sheet
     * @return Cell
     */
    public function setSheet($sheet)
    {
        $this->sheet = $sheet;

        return $this;
    }

    /**
     * @return string
     */
    public function getSpine()
    {
        return $this->spine;
    }

    /**
     * @param string $spine
     * @return Cell
     */
    public function setSpine($spine)
    {
        $this->spine = $spine;

        return $this;
    }

    /**
     * @return string
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param string $column
     * @return Cell
     */
    public function setColumn($column)
    {
        $this->column = $column;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        $variable = self::$types[$this->type];

        return $this->$variable;
    }

    /**
     * @param string $content
     * @return Cell
     */
    public function setContent($content)
    {
        $variable = self::$types[$this->type];

        $this->$variable = $content;

        return $this;
    }
}
