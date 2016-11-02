<?php

namespace Arkschools\DataInputSheet\Bridge\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ImportDataInputSheetSpinePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('arkschools.repository.data_input_sheet')) {
            return;
        }

        $definition = $container->findDefinition('arkschools.repository.data_input_sheet');

        /**
         * Spine services should have a tag and extend Spine class, example:
         *
         *    app.data_input_sheet.cars_spine:
         *      class: AppBundle\DataInputSheet\CarSpine
         *      arguments:
         *          - @repositories.cars
         *      tags:
         *          - { name: data_input_sheet.spine, sheet: cars }
         */

        $taggedServices = $container->findTaggedServiceIds('data_input_sheet.spine');
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
