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
     * @var string
     */
    private $tableName;

    /**
     * Sheet constructor.
     *
     * @param string $name
     * @param View[] $views
     * @param string $tableName
     */
    public function __construct($name, array $views, $tableName = null)
    {
        $this->id        = \slugifier\slugify($name);
        $this->name      = $name;
        $this->views     = $views;
        $this->tableName = $tableName;
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

    /**
     * @return null|string
     */
    public function getTableName()
    {
        return $this->tableName;
    }
}
