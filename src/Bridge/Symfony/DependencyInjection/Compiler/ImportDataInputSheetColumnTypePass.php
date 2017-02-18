<?php

namespace Arkschools\DataInputSheet\Bridge\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ImportDataInputSheetColumnTypePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('arkschools.repository.data_input_sheet')) {
            return;
        }

        $definition = $container->findDefinition('arkschools.factory.data_input_sheet_column');

        /**
         * ColumnType services should have a tag and extend ColumnBase class, example:
         *
         *    app.data_input_sheet.grade_column:
         *      class: AppBundle\DataInputSheet\GradeColumnType
         *      arguments:
         *          - @repositories.grade
         *      tags:
         *          - { name: data_input_sheet.column, type: grade }
         */

        $taggedServices = $container->findTaggedServiceIds('data_input_sheet.column');
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall(
                    'addColumnType', array(
                    new Reference($id),
                    $attributes['type'],
                )
                );
            }
        }
    }
}
