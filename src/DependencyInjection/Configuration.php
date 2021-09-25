<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('itb_api_platform_utilities');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('input_transformations')
                    ->defaultValue([])
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('request_class')
                                ->isRequired()
                                ->validate()
                                    ->ifTrue(static function($requestClass) {
                                        $test = is_string($requestClass);
                                        return !is_string($requestClass);
                                    })
                                    ->thenInvalid('request_class class must be a string.')
                                    ->ifTrue(static function($requestClass) {
                                        return !class_exists($requestClass);
                                    })
                                    ->thenInvalid('request_class must be a valid class name (fully qualified).')
                                ->end()
                            ->end()
                            ->scalarNode('object_class')
                                ->isRequired()
                                ->validate()
                                    ->ifTrue(static function($objectClass) {
                                        return !is_string($objectClass);
                                    })
                                    ->thenInvalid('object_class must be a string.')
                                    ->ifTrue(static function($objectClass) {
                                        return !class_exists($objectClass);
                                    })
                                    ->thenInvalid('object_class must be a valid class name (fully qualified).')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('output_transformations')
                    ->defaultValue([])
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('object_class')
                                ->isRequired()
                                ->validate()
                                    ->ifTrue(static function($objectClass) {
                                        return !is_string($objectClass);
                                    })
                                    ->thenInvalid('object_class must be a string.')
                                    ->ifTrue(static function($objectClass) {
                                        return !class_exists($objectClass);
                                    })
                                    ->thenInvalid('object_class must be a valid class name (fully qualified).')
                                ->end()
                            ->end()
                            ->scalarNode('response_class')
                                ->isRequired()
                                ->validate()
                                    ->ifTrue(static function($responseClass) {
                                        return !is_string($responseClass);
                                    })
                                    ->thenInvalid('response_class class must be a string.')
                                    ->ifTrue(static function($responseClass) {
                                        return !class_exists($responseClass);
                                    })
                                    ->thenInvalid('response_class must be a valid class name (fully qualified).')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}