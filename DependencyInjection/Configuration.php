<?php

namespace Akyos\PuppeteerSDK\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('puppeteer_sdk');

        $treeBuilder
            ->getRootNode()
                ->children()
                    ->arrayNode('token')
                        ->children()
                            ->scalarNode('key')->defaultValue('%env(PUPPETEERSDK_KEY)%')->end()
                            ->scalarNode('algo')->defaultValue('HS256')->end()
                            ->scalarNode('validity_time')->defaultValue('60')->end()
                        ->end()
                    ->end()
                    ->arrayNode('options')
                        ->children()
                            ->scalarNode('format')->defaultValue('A4')->end()
                            ->scalarNode('printBackground')->defaultTrue()->end()
                            ->scalarNode('scale')->defaultValue(1)->end()
                            ->scalarNode('landscape')->defaultFalse()->end()
                            ->arrayNode('margin')
                                ->children()
                                    ->scalarNode('top')->defaultValue(0)->end()
                                    ->scalarNode('right')->defaultValue(0)->end()
                                    ->scalarNode('bottom')->defaultValue(0)->end()
                                    ->scalarNode('left')->defaultValue(0)->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('endpoints')
                        ->defaultValue(['https://puppeteer.ac-dev.fr'])
                        ->scalarPrototype()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}