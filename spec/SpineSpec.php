<?php

namespace spec\Arkschools\DataInputSheet;

use PhpSpec\ObjectBehavior;

class SpineSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('cars', [
            'lexus-is-200'    => 'Lexus IS 200 1999 - 2005',
            'audi-80'         => 'Audi 80 1.6 E 1992 - 1994',
            'hyundai-i20'     => 'Hyundai i20 1.25 i-Motion 2010 - 2012',
            'renault-fluence' => 'Renault Fluence Z.E. Expression 2011 - 2015',
            'hyundai-i40'     => 'Hyundai i40 1.6 GDI Blue i-Motion 2011 - 2014',
        ]);
    }

    function it_has_a_header()
    {
        $this->getHeader()->shouldReturn('cars');
    }

    function it_has_spine_data()
    {
        $this->getSpine()->shouldReturn([
            'lexus-is-200'    => 'Lexus IS 200 1999 - 2005',
            'audi-80'         => 'Audi 80 1.6 E 1992 - 1994',
            'hyundai-i20'     => 'Hyundai i20 1.25 i-Motion 2010 - 2012',
            'renault-fluence' => 'Renault Fluence Z.E. Expression 2011 - 2015',
            'hyundai-i40'     => 'Hyundai i40 1.6 GDI Blue i-Motion 2011 - 2014',
        ]);
    }
}
