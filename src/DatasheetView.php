<?php

namespace Aleherse\Datasheet;

class DatasheetView
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $id;

    /**
     * @var array
     */
    private $columns;

    public function __construct($title, array $columns)
    {
        $this->id = \slugifier\slugify($title);
        $this->title = $title;
        $this->columns = $columns;
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
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }
}
