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
         *      schools:
         *          views:
         *              "Brand and model": ["Brand name", "Model name", "Description"]
         *              "Performance": ["Brand name", "Top speed", "Acceleration"]
         *          columns:
         *              "Brand name": string
         *              "Model name": string
         *              "Description": text
         *              "Top speed": integer
         *              "Acceleration": double
         */
        $rootNode
            ->useAttributeAsKey('name')
            ->prototype('array')
            ->children()
                ->arrayNode('views')
                    ->prototype('array')
                        ->prototype('scalar')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('columns')
                    ->prototype('scalar')
                        ->isRequired()
                        ->validate()
                        ->ifNotInArray(array('integer', 'double', 'string', 'text'))
                            ->thenInvalid('Invalid datasheet column type %s')
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
