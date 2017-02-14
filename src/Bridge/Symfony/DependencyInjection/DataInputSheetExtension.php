<?php

namespace Arkschools\DataInputSheet\Bridge\Symfony\DependencyInjection;

use Arkschools\DataInputSheet\ColumnFactory;
use Arkschools\DataInputSheet\DataInputSheetRepository;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DataInputSheetExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor     = new Processor();
        $configuration = new Configuration();
        $config        = $processor->processConfiguration($configuration, $configs);

        $container->addDefinitions([
            'arkschools.factory.data_input_sheet_column' => new Definition(
                ColumnFactory::class,
                [$config['extra_column_types']]
            )
        ]);

        $container->addDefinitions([
            'arkschools.repository.data_input_sheet' => new Definition(
                DataInputSheetRepository::class,
                [
                    new Reference('doctrine'),
                    new Reference('arkschools.factory.data_input_sheet_column'),
                    $config['sheets'],
                    new Parameter('data_input_sheet.entity_manager_name')
                ]
            )
        ]);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}
