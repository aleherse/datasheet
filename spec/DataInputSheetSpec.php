<?php

namespace spec\Arkschools\DataInputSheet;

use Arkschools\DataInputSheet\Spine;
use Arkschools\DataInputSheet\View;
use PhpSpec\ObjectBehavior;

class DataInputSheetSpec extends ObjectBehavior
{
    function let(Spine $spine, View $brand, View $drive)
    {
        $brand->getId()->willReturn('brand');
        $drive->getId()->willReturn('drive');
        $spine->getSpine()->willReturn([
            'Lexus IS 200 1999 - 2005',
            'Audi 80 1.6 E 1992 - 1994',
            'Hyundai i20 1.25 i-Motion 2010 - 2012',
            'Renault Fluence Z.E. Expression 2011 - 2015',
            'Hyundai i40 1.6 GDI Blue i-Motion 2011 - 2014'
        ]);

        $this->beConstructedWith('cars', $spine, [$brand, $drive]);
    }

    function it_has_an_id()
    {
        $this->getId()->shouldReturn('cars');
    }


    function it_has_a_name()
    {
        $this->getName()->shouldReturn('cars');
    }

    function it_has_spine_data()
    {
        $this->getSpine()->shouldReturn([
            'Lexus IS 200 1999 - 2005',
            'Audi 80 1.6 E 1992 - 1994',
            'Hyundai i20 1.25 i-Motion 2010 - 2012',
            'Renault Fluence Z.E. Expression 2011 - 2015',
            'Hyundai i40 1.6 GDI Blue i-Motion 2011 - 2014'
        ]);
    }

    function it_has_different_collections_of_views(View $brand, View $drive)
    {
        $this->getViews()->shouldReturn([$brand, $drive]);
    }

    function it_retrieves_a_view_by_id(View $brand)
    {
        $this->getView('brand')->shouldReturn($brand);
    }
}
