<?php

namespace Arkschools\DataInputSheets\Bridge\Symfony\Entity;

use Arkschools\DataInputSheets\Column;

class Cell
{
    protected static $types = [
        Column::BOOL     => 'contentBoolean',
        Column::INTEGER  => 'contentInteger',
        Column::FLOAT    => 'contentFloat',
        Column::STRING   => 'contentString',
        Column::TEXT     => 'contentText',
        Column::DATETIME => 'contentDate',
    ];

    /**
     * @var string
     */
    protected $sheet;

    /**
     * @var string
     */
    protected $column;

    /**
     * @var string
     */
    protected $spine;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var integer
     */
    protected $contentBoolean;

    /**
     * @var integer
     */
    protected $contentInteger;

    /**
     * @var float
     */
    protected $contentFloat;

    /**
     * @var string
     */
    protected $contentString;

    /**
     * @var string
     */
    protected $contentText;

    /**
     * @var \DateTime
     */
    protected $contentDate;

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
     *
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
     *
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
     *
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
     *
     * @return Cell
     */
    public function setContent($content)
    {
        $variable = self::$types[$this->type];

        $this->$variable = $content;

        return $this;
    }
}
