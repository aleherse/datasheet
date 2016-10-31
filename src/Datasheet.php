<?php

namespace Aleherse\Datasheet;

class Datasheet
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
     * @var DatasheetStub
     */
    private $stub;

    /**
     * @var DatasheetView[]
     */
    private $views;

    /**
     * Datasheet constructor.
     *
     * @param                 $name
     * @param DatasheetStub   $stub
     * @param DatasheetView[] $views
     */
    public function __construct($name, DatasheetStub $stub, array $views)
    {
        $this->stub = $stub;
        $this->id = \slugifier\slugify($name);
        $this->name = $name;

        $this->views = [];
        foreach ($views as $view) {
            $this->views[$view->getId()] = $view;
        }
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
    public function getStub()
    {
        return $this->stub->getStub();
    }

    /**
     * @return DatasheetView[]
     */
    public function getViews()
    {
        return array_values($this->views);
    }

    /**
     * @param string $viewId
     *
     * @return DatasheetView|null
     */
    public function getView($viewId)
    {
        return (isset($this->views[$viewId])) ? $this->views[$viewId] : null;
    }
}
