<?php

namespace spec\Arkschools\DataInputSheet\ColumnType;

use Arkschools\DataInputSheet\Bridge\Symfony\Entity\Cell;
use Arkschools\DataInputSheet\Column;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ColumnFloatSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Acceleration');
    }

    function it_extends_column()
    {
        $this->shouldBeAnInstanceOf(Column::class);
    }

    function it_has_an_id()
    {
        $this->getId()->shouldReturn('acceleration');
    }

    function it_has_a_title()
    {
        $this->getTitle()->shouldReturn('Acceleration');
    }

    function it_has_a_type()
    {
        $this->getType()->shouldReturn(Column::FLOAT);
    }

    function it_has_a_cell_type()
    {
        $this->getCellType()->shouldReturn(Cell::TYPE_FLOAT);
    }

    function it_creates_a_cell()
    {
        $this->createCell('cars', 'Lexus-is-200', 9.5)->shouldBeLike(
            new Cell('cars', 'acceleration', 'Lexus-is-200', Cell::TYPE_FLOAT, 9.5)
        );
    }
}
