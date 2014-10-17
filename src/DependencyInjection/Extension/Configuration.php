<?php

namespace Flo\Torrentz\DependencyInjection\Extension;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
        $rootNode = $treeBuilder->root('torrentz');
        $rootNode
            ->children()
                ->append( $this->getDoctrineConfigSection() )
            ->end()
        ;
        return $treeBuilder;
    }

    private function getDoctrineConfigSection($name = 'doctrine')
    {
        $builder = new TreeBuilder();
        $node = $builder->root($name);
        return $node
            ->addDefaultsIfNotSet()
            ->info('Doctrine config')
            ->children()
                ->scalarNode('driver')->defaultValue('pdo_mysql')->end()
                ->scalarNode('host')->defaultValue('localhost')->end()
                ->scalarNode('username')->defaultValue('root')->end()
                ->scalarNode('password')->defaultValue('')->end()
                ->booleanNode('dbname')->defaultValue('torrentz')->end()
            ->end()
        ;
    }
}