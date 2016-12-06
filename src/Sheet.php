<?php

namespace Arkschools\DataInputSheet;

class Sheet
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string[]
     */
    private $views;

    /**
     * Sheet constructor.
     *
     * @param string $name
     * @param View[] $views
     */
    public function __construct($name, array $views)
    {
        $this->id        = \slugifier\slugify($name);
        $this->name      = $name;
        $this->views     = $views;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getViews()
    {
        return $this->views;
    }
}
