<?php

namespace spec\Aleherse\Datasheet;

use Aleherse\Datasheet\DatasheetColumn;
use PhpSpec\ObjectBehavior;

class DatasheetViewSpec extends ObjectBehavior
{
    function let(DatasheetColumn $brand, DatasheetColumn $model)
    {
        $this->beConstructedWith('Brand and model', [$brand, $model]);
    }

    function it_has_an_id()
    {
        $this->getId()->shouldReturn('brand-and-model');
    }

    function it_has_a_title()
    {
        $this->getTitle()->shouldReturn('Brand and model');
    }

    function it_has_columns(DatasheetColumn $brand, DatasheetColumn $model)
    {
        $this->getColumns()->shouldReturn([$brand, $model]);
    }
}
