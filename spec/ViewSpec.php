<?php

namespace spec\Arkschools\DataInputSheets;

use Arkschools\DataInputSheets\Bridge\Symfony\Entity\Cell;
use Arkschools\DataInputSheets\Column;
use Arkschools\DataInputSheets\Selector\AbstractSelector;
use Arkschools\DataInputSheets\Spine;
use Arkschools\DataInputSheets\View;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

class ViewSpec extends ObjectBehavior
{
    function let(Spine $spine, Column $brand, Column $model, Cell $cell, AbstractSelector $selector)
    {
        $cell->getColumn()->willReturn('brand');
        $cell->getSpine()->willReturn('renault-11');
        $model->getId()->willReturn('model');
        $brand->getId()->willReturn('brand');
        $spine->getHeader()->willReturn('Cars');
        $spine->getSpine()->willReturn(
            [
                'lexus-is-200'    => 'Lexus IS 200 1999 - 2005',
                'audi-80'         => 'Audi 80 1.6 E 1992 - 1994',
                'hyundai-i20'     => 'Hyundai i20 1.25 i-Motion 2010 - 2012',
                'renault-fluence' => 'Renault Fluence Z.E. Expression 2011 - 2015',
                'hyundai-i40'     => 'Hyundai i40 1.6 GDI Blue i-Motion 2011 - 2014',
            ]
        );

        $spine->hasSpine('lexus-is-200')->willReturn(true);
        $spine->count()->willReturn(5);
        $spine->getSpineFromId('lexus-is-200')->willReturn('Lexus IS 200 1999 - 2005');
        $spine->getSpineObject('lexus-is-200')->willReturn(null);
        $spine->getSpineIdFromPosition(3)->willReturn('renault-fluence');
        $spine->getEntity()->willReturn(null);
        $spine->getEntitySpineField()->willReturn(null);
        $spine->getTableName()->willReturn(null);
        $spine->setFilters(Argument::any())->willReturn(null);
        $spine->getFilters()->willReturn(['age' => '>30']);

        $selector->isRequired()->willReturn(true);
        $selector->getFilters()->willReturn(['age' => '<5']);

        $this->beConstructedWith(
            'cars',
            'Brand and model',
            $spine,
            ['age' => '>30'],
            [$brand, $model],
            ['brand' => true],
            $selector
        );
    }

    function it_has_an_id()
    {
        $this->getId()->shouldReturn('brand-and-model');
    }

    function it_has_a_sheet_id()
    {
        $this->getSheetId()->shouldReturn('cars');
    }

    function it_has_a_title()
    {
        $this->getTitle()->shouldReturn('Brand and model');
    }

    function it_has_a_spine_header()
    {
        return $this->getSpineHeader()->shouldReturn('Cars');
    }

    function it_returns_spine_elements()
    {
        $this->getSpine()->shouldReturn(
            [
                'lexus-is-200'    => 'Lexus IS 200 1999 - 2005',
                'audi-80'         => 'Audi 80 1.6 E 1992 - 1994',
                'hyundai-i20'     => 'Hyundai i20 1.25 i-Motion 2010 - 2012',
                'renault-fluence' => 'Renault Fluence Z.E. Expression 2011 - 2015',
                'hyundai-i40'     => 'Hyundai i40 1.6 GDI Blue i-Motion 2011 - 2014',
            ]
        );
    }

    function it_returns_a_spine_element_by_its_id()
    {
        $this->getSpineFromId('lexus-is-200')->shouldReturn('Lexus IS 200 1999 - 2005');
    }

    function it_returns_a_spine_object_by_its_id()
    {
        // Spine objects should be populated in the load method

        $this->getSpineObjectFromId('lexus-is-200')->shouldReturn(null);
    }

    function it_returns_spine_id_of_the_element_in_an_specific_position_starting_from_0()
    {
        $this->getSpineIdFromPosition(3)->shouldReturn('renault-fluence');
    }

    function it_returns_the_columns(Column $brand, Column $model)
    {
        $this->getColumns()->shouldReturn(['brand' => $brand, 'model' => $model]);
    }

    function it_returns_the_visible_columns(Column $model)
    {
        $this->getVisibleColumns()->shouldReturn(['model' => $model]);
    }

    function it_checks_if_it_has_hidden_columns()
    {
        $this->hasHiddenColumns()->shouldReturn(true);
    }

    function it_returns_a_column_by_its_id(Column $model)
    {
        $this->getColumn('model')->shouldReturn($model);
        $this->getColumn('unknown')->shouldReturn(null);
    }

    function it_checks_if_it_has_a_column()
    {
        $this->hasColumn('model')->shouldReturn(true);
        $this->hasColumn('unknown')->shouldReturn(false);
    }

    function it_has_empty_content_if_not_previously_loaded()
    {
        $this->getContent('brand', 'lexus-is-200')->shouldReturn(null);
    }

    function it_checks_if_it_has_a_spine_element()
    {
        $this->hasSpine('lexus-is-200')->shouldReturn(true);
    }

    function it_returns_the_number_of_elements_in_the_spine()
    {
        $this->count()->shouldReturn(5);
    }

    function it_extract_the_sheet_data_from_the_request()
    {
        $request = new Request(
            [], [
            View::FORM_NAME => [
                'lexus-is-200' => ['brand' => 'Lexus', 'model' => 'IS 200 1999 - 2005'],
                'audi-80'      => ['brand' => 'Audi', 'model' => '80 1.6 E 1992 - 1994'],
            ],
        ]
        );

        $this->extractDataFromRequest($request)->shouldReturn(
            [
                'lexus-is-200' => ['brand' => 'Lexus', 'model' => 'IS 200 1999 - 2005'],
                'audi-80'      => ['brand' => 'Audi', 'model' => '80 1.6 E 1992 - 1994'],
            ]
        );
    }

    function it_checks_if_a_selection_step_is_required()
    {
        $this->isSelectionRequired()->shouldReturn(true);
    }

    function it_applies_the_selection_present_in_the_request(Request $request, AbstractSelector $selector)
    {
        $selector->applyFilters($request)->shouldBeCalled();

        $this->applySelection($request);
    }

    function it_returns_the_rendered_selector_template(\Twig_Environment $twig, AbstractSelector $selector)
    {
        $selector->render($twig, ['age' => '>30'])->willReturn('Selector Template');

        $this->renderSelector($twig)->shouldReturn('Selector Template');
    }

    function it_returns_the_selector_filters()
    {
        $this->getSelectorFilters()->shouldReturn(['age' => '<5']);
    }
}
