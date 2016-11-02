<?php

namespace Arkschools\DataInputSheet;

class Spine
{
    /**
     * @var string
     */
    protected $header;

    /**
     * @var string[]
     */
    protected $spine;

    public function __construct($header, array $spine)
    {
        $this->header = $header;
        $this->spine = $spine;
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
    public function getSpine()
    {
        return $this->spine;
    }
}
