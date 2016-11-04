<?php

namespace spec\Arkschools\DataInputSheet;

use Arkschools\DataInputSheet\Column;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ColumnFactorySpec extends ObjectBehavior
{
    function it_has_a_factory_method_by_integer()
    {
        $this->beConstructedThrough('create', ['integer', 'Brand name']);

        $this->getType()->shouldReturn(Column::INTEGER);
    }

    function it_has_a_factory_method_by_float()
    {
        $this->beConstructedThrough('create', ['float', 'Brand name']);

        $this->getType()->shouldReturn(Column::FLOAT);
    }

    function it_has_a_factory_method_by_text()
    {
        $this->beConstructedThrough('create', ['text', 'Brand name']);

        $this->getType()->shouldReturn(Column::TEXT);
    }

    function it_defaults_to_text_if_type_does_not_exist()
    {
        $this->beConstructedThrough('create', ['unknown', 'Brand name']);

        $this->getType()->shouldReturn(Column::TEXT);
    }
}
