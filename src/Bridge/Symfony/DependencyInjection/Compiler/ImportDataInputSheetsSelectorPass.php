<?php

namespace Arkschools\DataInputSheets\Bridge\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ImportDataInputSheetsSelectorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('arkschools.repository.data_input_sheets')) {
            return;
        }

        $definition = $container->findDefinition('arkschools.factory.data_input_sheets_selector');

        /**
         * ColumnType services should have a tag and extend AbstractColumn class, example:
         *
         *    AppBundle\DataInputSheets\DealerSelector:
         *      tags:
         *          - { name: data_input_sheets.selector, type: dealer }
         */

        $taggedServices = $container->findTaggedServiceIds('data_input_sheets.selector');
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall(
                    'addSelector', array(
                    new Reference($id),
                    $attributes['type'],
                )
                );
            }
        }
    }
}
