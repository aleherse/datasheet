<?php

namespace spec\Aleherse\Datasheet;

use PhpSpec\ObjectBehavior;

class DatasheetStubSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([
            'Lexus IS 200 1999 - 2005',
            'Audi 80 1.6 E 1992 - 1994',
            'Hyundai i20 1.25 i-Motion 2010 - 2012',
            'Renault Fluence Z.E. Expression 2011 - 2015',
            'Hyundai i40 1.6 GDI Blue i-Motion 2011 - 2014'
        ]);
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
}
