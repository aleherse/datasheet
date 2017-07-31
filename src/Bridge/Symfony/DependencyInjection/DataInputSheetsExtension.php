<?php

namespace Arkschools\DataInputSheets\Bridge\Symfony\DependencyInjection;

use Arkschools\DataInputSheets\ColumnFactory;
use Arkschools\DataInputSheets\DataInputSheetsRepository;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DataInputSheetsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor     = new Processor();
        $configuration = new Configuration();
        $config        = $processor->processConfiguration($configuration, $configs);

        $container->addDefinitions(
            [
                'arkschools.factory.data_input_sheets_column' => new Definition(
                    ColumnFactory::class,
                    [
                        $config['extra_column_types'],
                        new Reference('service_container'),
                    ]
                ),
            ]
        );

        $container->addDefinitions(
            [
                'arkschools.repository.data_input_sheets' => new Definition(
                    DataInputSheetsRepository::class,
                    [
                        new Reference('doctrine'),
                        new Reference('arkschools.factory.data_input_sheets_column'),
                        new Reference('arkschools.factory.data_input_sheets_selector'),
                        $config['sheets'],
                        new Parameter('data_input_sheets.entity_manager_name'),
                    ]
                ),
            ]
        );

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}
