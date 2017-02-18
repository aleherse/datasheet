<?php

namespace spec\Arkschools\DataInputSheets\ColumnType;

use Arkschools\DataInputSheets\Bridge\Symfony\Entity\Cell;
use Arkschools\DataInputSheets\Bridge\Symfony\Entity\CustomCell;
use Arkschools\DataInputSheets\Column;
use PhpSpec\ObjectBehavior;

class FloatColumnSpec extends ObjectBehavior
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

    function it_has_a_DB_type()
    {
        $this->getDBType()->shouldReturn(Column::FLOAT);
    }

    function it_creates_a_cell()
    {
        $this->createCell('cars', 'Lexus-is-200', 9.5)->shouldBeLike(
            new Cell('cars', 'acceleration', 'Lexus-is-200', Column::FLOAT, 9.5)
        );
    }

    function it_creates_a_custom_cell()
    {
        $this->createCell('cars', 'Lexus-is-200', 9.5, true)->shouldBeLike(
            new CustomCell('acceleration', 'Lexus-is-200', Column::FLOAT, 9.5)
        );
    }
}
