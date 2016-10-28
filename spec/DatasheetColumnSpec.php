<?php

namespace spec\Aleherse\Datasheet;

use Aleherse\Datasheet\DatasheetColumn;
use PhpSpec\ObjectBehavior;

class DatasheetColumnSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedThrough('integer', ['Brand name']);
    }

    function it_has_an_id()
    {
        $this->getId()->shouldReturn('brand-name');
    }

    function it_has_a_title()
    {
        $this->getTitle()->shouldReturn('Brand name');
    }

    function it_has_a_type()
    {
        $this->getType()->shouldReturn(DatasheetColumn::INTEGER);
    }

    function it_has_a_factory_method_by_integer()
    {
        $this->beConstructedThrough('integer', ['Brand name']);

        $this->getType()->shouldReturn(DatasheetColumn::INTEGER);
    }

    function it_has_a_factory_method_by_double()
    {
        $this->beConstructedThrough('double', ['Brand name']);

        $this->getType()->shouldReturn(DatasheetColumn::DOUBLE);
    }

    function it_has_a_factory_method_by_string()
    {
        $this->beConstructedThrough('string', ['Brand name']);

        $this->getType()->shouldReturn(DatasheetColumn::STRING);
    }

    function it_has_a_factory_method_by_text()
    {
        $this->beConstructedThrough('text', ['Brand name']);

        $this->getType()->shouldReturn(DatasheetColumn::TEXT);
    }
}
