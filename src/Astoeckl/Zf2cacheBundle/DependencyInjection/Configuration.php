<?php

namespace Astoeckl\Zf2cacheBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('zf2cache');

        $rootNode
            ->children()
                ->arrayNode('adapter')
                    ->children()
                        ->scalarNode('name')->end()
                        ->arrayNode('options')
                            ->children()
                                ->arrayNode('servers')
                                    ->prototype('scalar')->end()
                                ->end()
                                ->scalarNode('namespace')->end()
                                ->scalarNode('ttl')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('plugins')
                    ->children()
                        ->arrayNode('exception_handler')->end()
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
