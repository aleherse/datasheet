<?php

namespace spec\Arkschools\DataInputSheets;

use Arkschools\DataInputSheets\Column;
use Arkschools\DataInputSheets\ColumnType\DateColumn;
use Arkschools\DataInputSheets\ColumnType\FloatColumn;
use Arkschools\DataInputSheets\ColumnType\GenderColumn;
use Arkschools\DataInputSheets\ColumnType\IntegerColumn;
use Arkschools\DataInputSheets\ColumnType\ObjectValueColumn;
use Arkschools\DataInputSheets\ColumnType\ServiceListColumn;
use Arkschools\DataInputSheets\ColumnType\StringColumn;
use Arkschools\DataInputSheets\ColumnType\TextColumn;
use Arkschools\DataInputSheets\ColumnType\YesNoColumn;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ColumnFactorySpec extends ObjectBehavior
{
    function let(ContainerInterface $container)
    {
        $this->beConstructedWith([], $container);
    }

    function it_creates_an_integer_column()
    {
        $this->create(['type' => 'integer'], 'Doors')
            ->shouldBeLike(new Column(new IntegerColumn(), 'Doors'));
    }

    function it_creates_a_float_column()
    {
        $this->create(['type' => 'float'], 'Speed')
            ->shouldBeLike(new Column(new FloatColumn(), 'Speed'));
    }

    function it_creates_a_string_column()
    {
        $this->create(['type' => 'string'], 'Brand name')
            ->shouldBeLike(new Column(new StringColumn(), 'Brand name'));
    }

    function it_creates_a_text_column()
    {
        $this->create(['type' => 'text'], 'Brand name')
            ->shouldBeLike(new Column(new TextColumn(), 'Brand name'));
    }

    function it_creates_a_date_column()
    {
        $this->create(['type' => 'date'], 'Manufacturing date')
            ->shouldBeLike(new Column(new DateColumn(), 'Manufacturing date'));
    }

    function it_creates_a_yes_no_column()
    {
        $this->create(['type' => 'yes/no'], 'All included')
            ->shouldBeLike(new Column(new YesNoColumn(), 'All included'));
    }

    function it_creates_a_service_list_column(ContainerInterface $container)
    {
        $this->create(['type' => 'serviceList', 'option' => ['app.data_input_sheets.car_lists', 'getCarDesigns']], 'Car design')
            ->shouldBeLike(new Column(new ServiceListColumn($container->getWrappedObject()), 'Car design', null, ['app.data_input_sheets.car_lists', 'getCarDesigns']));
    }

    function it_creates_a_method_name_column()
    {
        $this->create(['type' => '->getLength', 'option' => ['meters']], 'Car length')
            ->shouldBeLike(new Column(new ObjectValueColumn('getLength', ['meters']), 'Car length'));
    }

    function it_defaults_to_string_if_type_does_not_exist()
    {
        $this->create(['type' => 'unknown'], 'Brand name')
            ->shouldBeLike(new Column(new StringColumn(), 'Brand name'));
    }

    function it_can_create_a_column_with_an_entity_field_linked()
    {
        $this->create(['type' => 'string', 'field' => 'brand'], 'Brand name')
            ->shouldBeLike(new Column(new StringColumn(), 'Brand name', 'brand'));
    }

    function it_can_create_a_read_only_column()
    {
        $this->create(['type' => 'string', 'field' => 'brand', 'read_only' => true], 'Brand name')
            ->shouldBeLike(new Column(new StringColumn(), 'Brand name', 'brand', null, true));
    }
}
