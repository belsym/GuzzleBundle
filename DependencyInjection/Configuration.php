<?php

namespace Belsym\GuzzleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class Configuration implements ConfigurationInterface
{
    private $debug;

    /**
     * Constructor.
     *
     * @param Boolean $debug The kernel.debug value
     */
    public function __construct($debug)
    {
        $this->debug = (Boolean) $debug;
    }

    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $builder
            ->root('guzzle')
                ->children()
                    ->arrayNode('service')
                        ->children()
                            ->arrayNode('configuration')
                                ->beforeNormalization()
                                ->ifString()
                                    ->then(function($v){
                                        return array('configuration_file' => $v);
                                    })
                                ->end()
                                ->children()
                                    ->arrayNode('includes')
                                        ->beforeNormalization()
                                        ->ifNull()
                                            ->thenEmptyArray()
                                        ->end()
                                        ->prototype('scalar')->end()
                                    ->end()
                                    ->arrayNode('services')
                                        ->beforeNormalization()
                                        ->ifNull()
                                            ->thenEmptyArray()
                                        ->end()
                                        ->prototype('array')->end()
                                    ->end()
                                    ->scalarNode('configuration_file')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->booleanNode('logging')->defaultValue($this->debug)->end()
                ->end()
            ->end();

        return $builder;
    }
}
