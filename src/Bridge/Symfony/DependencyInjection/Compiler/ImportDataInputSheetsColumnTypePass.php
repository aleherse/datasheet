<?php

namespace Arkschools\DataInputSheets\Bridge\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ImportDataInputSheetsColumnTypePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('arkschools.repository.data_input_sheets')) {
            return;
        }

        $definition = $container->findDefinition('arkschools.factory.data_input_sheets_column');

        /**
         * ColumnType services should have a tag and extend AbstractColumn class, example:
         *
         *    app.data_input_sheets.grade_column:
         *      class: AppBundle\DataInputSheets\GradeColumnType
         *      arguments:
         *          - @repositories.grade
         *      tags:
         *          - { name: data_input_sheets.column, type: grade }
         */

        $taggedServices = $container->findTaggedServiceIds('data_input_sheets.column');
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
