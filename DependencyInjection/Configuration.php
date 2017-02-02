<?php
namespace Dmank\GearmanBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('gearman');

        $rootNode
            ->children()
                ->arrayNode('servers')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('host')->end()
                        ->integerNode('port')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

}
