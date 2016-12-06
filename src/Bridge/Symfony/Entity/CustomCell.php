<?php

namespace Arkschools\DataInputSheet\Bridge\Symfony\Entity;

use Arkschools\DataInputSheet\Column;

class CustomCell
{
    protected static $types = [
        Column::INTEGER => 'contentInteger',
        Column::FLOAT   => 'contentFloat',
        Column::STRING  => 'contentString',
        Column::TEXT    => 'contentText'
    ];

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
     * @param string $column
     * @param string $spine
     * @param int    $type
     * @param string $content
     */
    public function __construct($column, $spine, $type, $content)
    {
        $this->column = $column;
        $this->spine  = $spine;
        $this->type   = $type;

        $this->setContent($content);
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
     * @return CustomCell
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
     * @return CustomCell
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
     * @return CustomCell
     */
    public function setContent($content)
    {
        $variable = self::$types[$this->type];

        $this->$variable = $content;

        return $this;
    }
}
