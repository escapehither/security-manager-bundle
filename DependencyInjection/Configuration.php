<?php

namespace StarterKit\SecurityManagerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('starter_kit_security_manager');
        $rootNode
            ->children()
                ->arrayNode('user_provider')
                     ->children()
                        ->scalarNode('class')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->info('This value is only used for the user provider.')
                        ->end()
                     ->end()
                ->end()
            ->end()
    ;

        return $treeBuilder;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}