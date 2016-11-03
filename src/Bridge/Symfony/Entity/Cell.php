<?php

namespace Arkschools\DataInputSheet\Bridge\Symfony\Entity;

class Cell
{
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
    private $content;

    /**
     * @param string $sheet
     * @param string $column
     * @param string $spine
     * @param string $content
     */
    public function __construct($sheet, $column, $spine, $content)
    {
        $this->sheet   = $sheet;
        $this->column  = $column;
        $this->spine   = $spine;
        $this->content = $content;
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
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Cell
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
}
