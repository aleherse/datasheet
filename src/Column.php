<?php

namespace Arkschools\DataInputSheet;

class Column
{
    const INTEGER = 0;
    const DOUBLE = 1;
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

    protected function __construct($title, $type)
    {
        $this->id = \slugifier\slugify($title);
        $this->title = $title;
        $this->type = $type;
    }

    /**
     * @param string $title
     *
     * @return Column
     */
    public static function integer($title)
    {
        return new Column($title, self::INTEGER);
    }

    /**
     * @param string $title
     *
     * @return Column
     */
    public static function double($title)
    {
        return new Column($title, self::DOUBLE);
    }

    /**
     * @param string $title
     *
     * @return Column
     */
    public static function string($title)
    {
        return new Column($title, self::STRING);
    }

    /**
     * @param string $title
     *
     * @return Column
     */
    public static function text($title)
    {
        return new Column($title, self::TEXT);
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
    public function getType()
    {
        return $this->type;
    }
}
