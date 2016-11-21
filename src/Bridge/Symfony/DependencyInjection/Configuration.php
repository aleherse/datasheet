<?php

namespace Arkschools\DataInputSheet\Bridge\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle.
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('data_input_sheet');

        /**
         *  Example:
         *
         *  data_input_sheet:
         *      config:
         *          entity_manager: "doctrine.orm.entity_manager"
         *      extra_column_types:
         *          color: AppBundle/DataInputSheet/ColumnType/Color
         *      sheets:
         *          cars:
         *              config:
         *                  table: data_input_sheet_cars
         *              views:
         *                  "Brand and model": ["Brand name", "Model name", "Description"]
         *                  "Performance": ["Brand name", "Top speed", "Acceleration"]
         *              columns:
         *                  "Brand name": string
         *                  "Model name": string
         *                  "Description": text
         *                  "Top speed": integer
         *                  "Acceleration": double
         */
        $rootNode
            ->children()
                ->arrayNode('config')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('entity_manager')
                            ->defaultValue('doctrine.orm.entity_manager')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('extra_column_types')
                ->useAttributeAsKey('name')
                ->defaultValue([])
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('sheets')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                    ->children()
                        ->arrayNode('config')
                            ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('table')
                                        ->defaultValue(null)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('views')
                            ->prototype('array')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                        ->arrayNode('columns')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
