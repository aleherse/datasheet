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
        $this->beConstructedWith('cars', [
            'lexus-is-200'    => 'Lexus IS 200 1999 - 2005',
            'audi-80'         => 'Audi 80 1.6 E 1992 - 1994',
            'hyundai-i20'     => 'Hyundai i20 1.25 i-Motion 2010 - 2012',
            'renault-fluence' => 'Renault Fluence Z.E. Expression 2011 - 2015',
            'hyundai-i40'     => 'Hyundai i40 1.6 GDI Blue i-Motion 2011 - 2014',
        ]);
    }

    function it_has_a_header()
    {
        $this->getHeader()->shouldReturn('cars');
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

    function it_might_have_a_custom_table_name_to_store_the_data()
    {
        $this->getTableName()->shouldReturn(null);
    }

    function it_might_have_a_custom_entity_to_store_the_data()
    {
        $this->getEntity()->shouldReturn(null);
    }

    function it_might_have_a_custom_entity_field_to_store_the_spine_id()
    {
        $this->getEntitySpineField()->shouldReturn('id');
    }

    function it_might_have_a_custom_query_builder(EntityManager $em, QueryBuilder $qb)
    {
        $qb->select(Argument::any())->willReturn($qb->getWrappedObject());
        $qb->from(Argument::cetera())->willReturn($qb->getWrappedObject());
        $em->createQueryBuilder()->willReturn($qb->getWrappedObject());

        $this->getQueryBuilder($em)->shouldReturn($qb);
    }
}
