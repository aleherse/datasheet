<?php

namespace spec\Arkschools\DataInputSheets\ColumnType;

use Arkschools\DataInputSheets\Bridge\Symfony\Entity\CustomCell;
use Arkschools\DataInputSheets\ColumnType\AbstractColumn;
use PhpSpec\ObjectBehavior;

class DateColumnSpec extends ObjectBehavior
{
    function it_extends_column()
    {
        $this->shouldBeAnInstanceOf(AbstractColumn::class);
    }

    function it_has_a_DB_type()
    {
        $this->getDBType()->shouldReturn(AbstractColumn::DATETIME);
    }

    function it_cast_the_column_value_to_a_proper_type()
    {
        $this->castCellContent('16/08/2017 12:20')
            ->shouldBeLike(\DateTime::createFromFormat('d/m/Y H:i', '16/08/2017 12:20'));

        $this->castCellContent('16/08/2017')
            ->shouldReturn(null);

        $this->castCellContent('16/08/2017', ['d/m/Y', 'DD/MM/YYYY'])
            ->shouldBeLike(\DateTime::createFromFormat('d/m/Y', '16/08/2017'));

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
            'DataInputSheetsBundle:extension:data_input_sheets_input_date_cell.html.twig',
            [
                'columnId' => 'manufacturing-date',
                'spineId'  => 'lexus-is-200',
                'content'  => '16/08/2017 12:20',
                'format'   => 'DD/MM/YYYY HH:MM',
                'readOnly' => false
            ]
        )->shouldBeCalled();

        $this->render($twig, 'manufacturing-date', 'lexus-is-200', \DateTime::createFromFormat('d/m/Y H:i', '16/08/2017 12:20'));

        $twig->render(
            'DataInputSheetsBundle:extension:data_input_sheets_input_date_cell.html.twig',
            [
                'columnId' => 'manufacturing-date',
                'spineId'  => 'lexus-is-200',
                'content'  => '16/08/2017',
                'format'   => 'DD/MM/YYYY',
                'readOnly' => false
            ]
        )->shouldBeCalled();

        $this->render($twig, 'manufacturing-date', 'lexus-is-200', \DateTime::createFromFormat('d/m/Y', '16/08/2017'), ['d/m/Y', 'DD/MM/YYYY']);
    }
}
