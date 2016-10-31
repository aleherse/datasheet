<?php

namespace spec\Aleherse\Datasheet;

use Aleherse\Datasheet\DatasheetColumn;
use Aleherse\Datasheet\DatasheetStub;
use PhpSpec\ObjectBehavior;

class DatasheetViewSpec extends ObjectBehavior
{
    function let(DatasheetStub $stub, DatasheetColumn $brand, DatasheetColumn $model)
    {
        $stub->getHeader()->willReturn('Cars');
        $stub->getStub()->willReturn([
            'Lexus IS 200 1999 - 2005',
            'Audi 80 1.6 E 1992 - 1994',
            'Hyundai i20 1.25 i-Motion 2010 - 2012',
            'Renault Fluence Z.E. Expression 2011 - 2015',
            'Hyundai i40 1.6 GDI Blue i-Motion 2011 - 2014'
        ]);

        $this->beConstructedWith('Brand and model', $stub, [$brand, $model]);
    }

    function it_has_an_id()
    {
        $this->getId()->shouldReturn('brand-and-model');
    }

    function it_has_a_title()
    {
        $this->getTitle()->shouldReturn('Brand and model');
    }

    function it_has_a_stub_header()
    {
        return $this->getStubHeader()->shouldReturn('Cars');
    }

    function it_has_stub_data()
    {
        $this->getStub()->shouldReturn([
            'Lexus IS 200 1999 - 2005',
            'Audi 80 1.6 E 1992 - 1994',
            'Hyundai i20 1.25 i-Motion 2010 - 2012',
            'Renault Fluence Z.E. Expression 2011 - 2015',
            'Hyundai i40 1.6 GDI Blue i-Motion 2011 - 2014'
        ]);
    }

    function it_has_columns(DatasheetColumn $brand, DatasheetColumn $model)
    {
        $this->getColumns()->shouldReturn([$brand, $model]);
    }
}
