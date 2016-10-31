<?php

namespace Aleherse\Datasheet\Bridge\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ImportDatasheetStubPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('aleherse.datasheet_repository')) {
            return;
        }

        $definition = $container->findDefinition('aleherse.datasheet_repository');

        /**
         * Stub services should have a tag and extend DatasheetStub class, example:
         *
         *    app.datasheet.cars_stub:
         *      class: AppBundle\Datasheet\CarDatasheetStub
         *      arguments:
         *          - @repositories.cars
         *      tags:
         *          - { name: datasheet.stub, datasheet: cars }
         */

        $taggedServices = $container->findTaggedServiceIds('datasheet.stub');
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall(
                    'addStub', array(
                    new Reference($id),
                    $attributes['datasheet'],
                )
                );
            }
        }
    }
}
