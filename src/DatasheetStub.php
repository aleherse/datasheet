<?php

namespace Aleherse\Datasheet;

class DatasheetStub
{
    /**
     * @var string[]
     */
    private $stub;

    public function __construct(array $stub)
    {
        $this->stub = $stub;
    }

    /**
     * @return string[]
     */
    public function getStub()
    {
        return $this->stub;
    }
}
