<?php

namespace Aleherse\Datasheet;

class Datasheet
{
    /**
     * @var DatasheetStub
     */
    private $stub;

    /**
     * @var DatasheetView[]
     */
    private $views;

    public function __construct(DatasheetStub $stub, array $views)
    {
        $this->stub = $stub;
        $this->views = $views;
    }

    /**
     * @return string[]
     */
    public function getStub()
    {
        return $this->stub->getStub();
    }

    /**
     * @return DatasheetView[]
     */
    public function getViews()
    {
        return $this->views;
    }
}
