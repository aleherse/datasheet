<?php

namespace spec\Arkschools\DataInputSheets;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SpineSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Cars', [
            'lexus-is-200'    => 'Lexus IS 200 1999 - 2005',
            'audi-80'         => 'Audi 80 1.6 E 1992 - 1994',
            'hyundai-i20'     => 'Hyundai i20 1.25 i-Motion 2010 - 2012',
            'renault-fluence' => 'Renault Fluence Z.E. Expression 2011 - 2015',
            'hyundai-i40'     => 'Hyundai i40 1.6 GDI Blue i-Motion 2011 - 2014',
        ]);
    }

    function it_has_a_header()
    {
        $this->getHeader()->shouldReturn('Cars');
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

    function it_checks_if_a_spine_id_exists()
    {
        $this->hasSpine('lexus-is-200')->shouldReturn(true);
        $this->hasSpine('unknown')->shouldReturn(false);
    }

    function it_returns_a_spine_element_by_its_id()
    {
        $this->getSpineFromId('lexus-is-200')->shouldReturn('Lexus IS 200 1999 - 2005');
    }

    function it_returns_a_spine_object_by_its_id()
    {
        // Spine objects should be populated in the load method

        $this->getSpineObject('lexus-is-200')->shouldReturn(null);
    }

    function it_returns_spine_id_of_the_element_in_an_specific_position_starting_from_0()
    {
        $this->getSpineIdFromPosition(3)->shouldReturn('renault-fluence');
    }

    function it_returns_the_number_if_spine_elements()
    {
        $this->count()->shouldReturn(5);
    }

    function it_can_have_a_custom_table_name_to_store_the_data()
    {
        $this->beConstructedWith('cars', ['lexus-is-200' => 'Lexus IS 200 1999 - 2005'], 'cars_table');

        $this->getTableName()->shouldReturn('cars_table');
    }

    function it_can_have_a_custom_entity_to_store_the_data()
    {
        $this->beConstructedWith('cars', ['lexus-is-200' => 'Lexus IS 200 1999 - 2005'], null, 'Namespace\Car');

        $this->getEntity()->shouldReturn('Namespace\Car');
    }

    function it_can_have_a_custom_entity_field_to_store_the_spine_id()
    {
        $this->beConstructedWith('cars', ['lexus-is-200' => 'Lexus IS 200 1999 - 2005'], null, null, 'spineId');

        $this->getEntitySpineField()->shouldReturn('spineId');
    }

    function it_defaults_to_id_for_the_entity_field_that_store_the_spine_id()
    {
        $this->getEntitySpineField()->shouldReturn('id');
    }

    function it_can_have_filters_to_be_used_when_the_spine_is_loaded()
    {
        // Filters have to be defined extending the class and overwriting the defaultFilter method
        // After that they can be modified using setFilters(['age' => '>30'])
        $this->setFilters([]);

        $this->getFilters()->shouldReturn([]);
    }

    function it_might_have_a_custom_query_builder(EntityManager $em, QueryBuilder $qb)
    {
        $qb->select(Argument::any())->willReturn($qb->getWrappedObject());
        $qb->from(Argument::cetera())->willReturn($qb->getWrappedObject());
        $em->createQueryBuilder()->willReturn($qb->getWrappedObject());

        $this->getQueryBuilder($em)->shouldReturn($qb);
    }
}
