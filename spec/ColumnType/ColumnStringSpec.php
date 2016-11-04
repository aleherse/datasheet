<?php

namespace spec\Arkschools\DataInputSheet\ColumnType;

use Arkschools\DataInputSheet\Column;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ColumnStringSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Brand name');
    }

    function it_extends_column()
    {
        $this->shouldHaveType(Column::class);
    }

    function it_has_a_type()
    {
        $this->getType()->shouldReturn(Column::STRING);
    }
}
