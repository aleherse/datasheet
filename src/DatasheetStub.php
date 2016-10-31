<?php

namespace Aleherse\Datasheet;

class DatasheetStub
{
    /**
     * @var string
     */
    protected $header;

    /**
     * @var string[]
     */
    protected $stub;

    public function __construct($header, array $stub)
    {
        $this->header = $header;
        $this->stub = $stub;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return string[]
     */
    public function getStub()
    {
        return $this->stub;
    }
}
