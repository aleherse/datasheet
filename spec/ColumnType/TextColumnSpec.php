<?php

namespace spec\Arkschools\DataInputSheets\ColumnType;

use Arkschools\DataInputSheets\Column;
use Arkschools\DataInputSheets\ColumnType\AbstractColumn;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TextColumnSpec extends ObjectBehavior
{
    function it_extends_column()
    {
        $this->shouldBeAnInstanceOf(AbstractColumn::class);
    }

    function it_has_a_DB_type()
    {
        $this->getDBType()->shouldReturn(AbstractColumn::STRING);
    }

    function it_cast_the_column_value_to_a_proper_type()
    {
        $this->castCellContent('Lexus')->shouldReturn('Lexus');
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
            'DataInputSheetsBundle:extension:data_input_sheets_textarea_cell.html.twig',
            [
                'columnId' => 'brand',
                'spineId'  => 'lexus-is-200',
                'content'  => 'Lexus',
                'readOnly' => false,
                'size'     => 60
            ]
        )->shouldBeCalled();

        $this->render($twig, 'brand', 'lexus-is-200', 'Lexus');
    }
}
