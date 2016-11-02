<?php

namespace Arkschools\DataInputSheet;

class DataInputSheet
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
     * @var Spine
     */
    private $spine;

    /**
     * @var View[]
     */
    private $views;

    /**
     * DataInputSheet constructor.
     *
     * @param                 $name
     * @param Spine   $spine
     * @param View[] $views
     */
    public function __construct($name, Spine $spine, array $views)
    {
        $this->spine = $spine;
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
    public function getSpine()
    {
        return $this->spine->getSpine();
    }

    /**
     * @return View[]
     */
    public function getViews()
    {
        return array_values($this->views);
    }

    /**
     * @param string $viewId
     *
     * @return View|null
     */
    public function getView($viewId)
    {
        return (isset($this->views[$viewId])) ? $this->views[$viewId] : null;
    }
}
