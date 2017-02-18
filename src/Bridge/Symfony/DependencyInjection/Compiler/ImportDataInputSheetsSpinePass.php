<?php

namespace Arkschools\DataInputSheets\Bridge\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ImportDataInputSheetsSpinePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('arkschools.repository.data_input_sheets')) {
            return;
        }

        $definition = $container->findDefinition('arkschools.repository.data_input_sheets');

        /**
         * Spine services should have a tag and extend Spine class, example:
         *
         *    app.data_input_sheets.cars_spine:
         *      class: AppBundle\DataInputSheets\CarSpine
         *      arguments:
         *          - @repositories.cars
         *      tags:
         *          - { name: data_input_sheets.spine, sheet: cars }
         */

        $taggedServices = $container->findTaggedServiceIds('data_input_sheets.spine');
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall(
                    'addSpine', array(
                    new Reference($id),
                    $attributes['sheet'],
                )
                );
            }
        }
    }
}
