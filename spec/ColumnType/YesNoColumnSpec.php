<?php

namespace spec\Arkschools\DataInputSheets\ColumnType;

use Arkschools\DataInputSheets\ColumnType\AbstractColumn;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class YesNoColumnSpec extends ObjectBehavior
{
    function it_extends_column()
    {
        $this->shouldBeAnInstanceOf(AbstractColumn::class);
    }

    function it_has_a_DB_type()
    {
        $this->getDBType()->shouldReturn(AbstractColumn::BOOL);
    }

    function it_cast_the_column_value_to_a_proper_type()
    {
        $this->castCellContent('Y')->shouldReturn(true);
        $this->castCellContent('Yes')->shouldReturn(true);
        $this->castCellContent('n')->shouldReturn(false);
        $this->castCellContent('NO')->shouldReturn(false);
        $this->castCellContent('unknown')->shouldReturn(null);
        $this->castCellContent('')->shouldReturn(null);
    }

    function it_check_if_the_column_has_a_value_that_need_to_be_retrieved_from_an_object()
    {
        $this->isValueColumn()->shouldReturn(false);
    }

    function it_get_the_value_from_an_object()
    {
        $this->getValue(new \StdClass())->shouldReturn('');
    }

    function it_checks_if_column_value_needs_to_be_stored()
    {
        $this->isStored()->shouldReturn(true);
    }

    function it_renders_the_form_element_to_capture_the_column_value(\Twig_Environment $twig)
    {
        $twig->render(
            'DataInputSheetsBundle:extension:data_input_sheets_yes_no_cell.html.twig',
            [
                'columnId' => 'is-new',
                'spineId'  => 'lexus-is-200',
                'content'  => true,
                'readOnly' => false,
                'size'     => 60
            ]
        )->shouldBeCalled();

        $this->render($twig, 'is-new', 'lexus-is-200', true);
    }
}
