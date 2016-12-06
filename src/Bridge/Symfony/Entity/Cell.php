<?php

namespace Arkschools\DataInputSheet\Bridge\Symfony\Entity;

class Cell extends CustomCell
{
    /**
     * @var string
     */
    protected $sheet;

    /**
     * @param string $sheet
     * @param string $column
     * @param string $spine
     * @param int    $type
     * @param string $content
     */
    public function __construct($sheet, $column, $spine, $type, $content)
    {
        parent::__construct($column, $spine, $type, $content);

        $this->sheet = $sheet;
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
}
