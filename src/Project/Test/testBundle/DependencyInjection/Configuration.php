<?php

namespace Project\Test\testBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('project_testtest');

        /** @noinspection PhpUndefinedMethodInspection */
        $rootNode
            ->children()
            ->scalarNode('transformer_namespace')
            ->isRequired()->cannotBeEmpty()
            ->end()
            ->variableNode('optional')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
