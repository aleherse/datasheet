<?php

namespace spec\Arkschools\DataInputSheets;

use Arkschools\DataInputSheets\Column;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ColumnFactorySpec extends ObjectBehavior
{
    function it_has_a_factory_method_by_integer()
    {
        $this->beConstructedThrough('create', [['type' => 'integer'], 'Brand name']);

        $this->getType()->shouldReturn(Column::INTEGER);
    }

    function it_has_a_factory_method_by_float()
    {
        $this->beConstructedThrough('create', [['type' => 'float'], 'Brand name']);

        $this->getType()->shouldReturn(Column::FLOAT);
    }

    function it_has_a_factory_method_by_string()
    {
        $this->beConstructedThrough('create', [['type' => 'string'], 'Brand name']);

        $this->getType()->shouldReturn(Column::STRING);
    }

    function it_has_a_factory_method_by_text()
    {
        $this->beConstructedThrough('create', [['type' => 'text'], 'Brand name']);

        $this->getType()->shouldReturn(Column::TEXT);
    }

    function it_defaults_to_string_if_type_does_not_exist()
    {
        $this->beConstructedThrough('create', [['type' => 'unknown'], 'Brand name']);

        $this->getType()->shouldReturn(Column::STRING);
    }

    function it_might_have_an_entity_field_linked_to_a_column()
    {
        $this->beConstructedThrough('create', [['type' => 'string', 'field' => 'brand'], 'Brand name']);

        $this->getType()->shouldReturn(Column::STRING);
        $this->getField()->shouldReturn('brand');
    }
}
