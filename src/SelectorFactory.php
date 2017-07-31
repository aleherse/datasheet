<?php

namespace Arkschools\DataInputSheets;

use Arkschools\DataInputSheets\Selector\AbstractSelector;

class SelectorFactory
{
    /**
     * @var AbstractSelector[]
     */
    private $selectors;

    public function addSelector(AbstractSelector $selector, string $name)
    {
        $this->selectors[$name] = $selector;
    }

    public function create(string $name): ?AbstractSelector
    {
        return $this->selectors[$name] ?? null;
    }
}
