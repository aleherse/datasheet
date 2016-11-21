<?php

namespace spec\Arkschools\DataInputSheet\ColumnType;

use Arkschools\DataInputSheet\Column;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ColumnTextSpec extends ObjectBehavior
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
        $this->getType()->shouldReturn(Column::TEXT);
    }

    function it_has_a_DB_type()
    {
        $this->getDBType()->shouldReturn(Column::TEXT);
    }
}
