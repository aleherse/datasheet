<?php

namespace Arkschools\DataInputSheets;

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
     * @var string[]
     */
    private $users;

    /**
     * Sheet constructor.
     *
     * @param string $id
     * @param string $name
     * @param View[] $views
     * @param string[] $users
     */
    public function __construct($id, $name, array $views, array $users)
    {
        $this->id    = $id;
        $this->name  = $name;
        $this->views = $views;
        $this->users = $users;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getViews(): array
    {
        return $this->views;
    }

    /**
     * @return string[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }
}
