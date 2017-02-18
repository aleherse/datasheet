<?php

namespace Arkschools\DataInputSheets\Bridge\Symfony\DependencyInjection;

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
        $rootNode = $treeBuilder->root('data_input_sheets');

        /**
         *  Example:
         *
         *  data_input_sheets:
         *      extra_column_types:
         *          color: AppBundle/DataInputSheets/ColumnType/Color
         *      sheets:
         *          cars:
         *              config:
         *                  table: data_input_sheets_cars
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
                ->arrayNode('extra_column_types')
                ->useAttributeAsKey('name')
                ->defaultValue([])
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('sheets')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                    ->children()
                        ->arrayNode('views')
                            ->prototype('array')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                        ->arrayNode('columns')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                            ->beforeNormalization()
                            ->ifString()
                                ->then(function($v) { return array('type' => $v, 'field' => null, 'option' => null); })
                            ->end()
                            ->children()
                                ->scalarNode('type')->isRequired()->end()
                                ->variableNode('option')->defaultValue(null)->end()
                                ->scalarNode('field')->defaultValue(null)
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
