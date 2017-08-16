<?php

namespace spec\Arkschools\DataInputSheets;

use PhpSpec\ObjectBehavior;

class SheetSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('cars', 'Cars', ['brand' => 'Car', 'drive' => 'Drive description']);
    }

    function it_has_an_id()
    {
        $this->getId()->shouldReturn('cars');
    }


    function it_has_a_name()
    {
        $this->getName()->shouldReturn('Cars');
    }

    function it_has_different_collections_of_views()
    {
        $this->getViews()->shouldReturn(['brand' => 'Car', 'drive' => 'Drive description']);
    }
}
