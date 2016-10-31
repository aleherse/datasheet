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
     * @var DatasheetStub
     */
    private $stub;

    /**
     * @var array
     */
    private $columns;

    public function __construct($title, DatasheetStub $stub, array $columns)
    {
        $this->id = \slugifier\slugify($title);
        $this->title = $title;
        $this->stub = $stub;
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
     * @return string
     */
    public function getStubHeader()
    {
        return $this->stub->getHeader();
    }

    /**
     * @return \string[]
     */
    public function getStub()
    {
        return $this->stub->getStub();
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }
}
