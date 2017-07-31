<?php

namespace Arkschools\DataInputSheets\Selector;

use Symfony\Component\HttpFoundation\Request;

abstract class AbstractSelector
{
    /**
     * @var array
     */
    protected $filters = [];

    abstract function render(\Twig_Environment $twig, array $filters): string;

    abstract public function applyFilters(Request $request): bool;

    abstract public function isRequired(): bool;

    public function getFilters(): array
    {
        return $this->filters;
    }
}
