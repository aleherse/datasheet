<?php

namespace spec\Arkschools\DataInputSheet;

use Arkschools\DataInputSheet\Spine;
use Arkschools\DataInputSheet\View;
use PhpSpec\ObjectBehavior;

class SheetSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Cars', ['brand' => 'Car', 'drive' => 'Drive description']);
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
