<?php

namespace spec\Arkschools\DataInputSheets\ColumnType;

use Arkschools\DataInputSheets\Column;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StringColumnSpec extends ObjectBehavior
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

    function it_has_a_DB_type()
    {
        $this->getDBType()->shouldReturn(Column::STRING);
    }
}
