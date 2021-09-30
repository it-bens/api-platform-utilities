<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('itb_api_platform_utilities');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->append($this->getInputTransformationsNode($rootNode))
                ->append($this->getOutputTransformationsNode($rootNode))
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * @param NodeDefinition $node
     * @return ArrayNodeDefinition
     */
    private function getInputTransformationsNode(NodeDefinition $node): ArrayNodeDefinition
    {
        $inputTransformationsNode = new ArrayNodeDefinition('input_transformations', $node);
        $inputTransformationsNode
            ->defaultValue([])
            ->arrayPrototype()
                ->children()
                    ->append($this->getRequestClassNode($node))
                    ->append($this->getObjectClassNode($node))
                ->end()
            ->end()
        ;

        return $inputTransformationsNode;
    }

    /**
     * @param NodeDefinition $node
     * @return ScalarNodeDefinition
     */
    private function getObjectClassNode(NodeDefinition $node): ScalarNodeDefinition
    {
        $objectClassNode = new ScalarNodeDefinition('object_class', $node);
        $objectClassNode
            ->isRequired()
            ->validate()
            ->ifTrue(static function ($objectClass) {
                return !is_string($objectClass);
            })
            ->thenInvalid('object_class must be a string.')
            ->ifTrue(static function ($objectClass) {
                return !class_exists($objectClass);
            })
            ->thenInvalid('object_class must be a valid class name (fully qualified).')
            ->end()
        ;

        return $objectClassNode;
    }

    /**
     * @param NodeDefinition $node
     * @return ArrayNodeDefinition
     */
    private function getOutputTransformationsNode(NodeDefinition $node): ArrayNodeDefinition
    {
        $outputTransformationsNode = new ArrayNodeDefinition('output_transformations', $node);
        $outputTransformationsNode
            ->defaultValue([])
            ->arrayPrototype()
                ->children()
                    ->append($this->getObjectClassNode($node))
                    ->append($this->getResponseClassNode($node))
                ->end()
            ->end()
        ;

        return $outputTransformationsNode;
    }

    /**
     * @param NodeDefinition $node
     * @return ScalarNodeDefinition
     */
    private function getRequestClassNode(NodeDefinition $node): ScalarNodeDefinition
    {
        $requestClassNode = new ScalarNodeDefinition('request_class', $node);
        $requestClassNode
            ->isRequired()
            ->validate()
            ->ifTrue(static function ($requestClass) {
                return !is_string($requestClass);
            })
            ->thenInvalid('request_class class must be a string.')
            ->ifTrue(static function ($requestClass) {
                return !class_exists($requestClass);
            })
            ->thenInvalid('request_class must be a valid class name (fully qualified).')
            ->end()
        ;

        return $requestClassNode;
    }

    /**
     * @param NodeDefinition $node
     * @return ScalarNodeDefinition
     */
    private function getResponseClassNode(NodeDefinition $node): ScalarNodeDefinition
    {
        $responseClassNode = new ScalarNodeDefinition('response_class', $node);
        $responseClassNode
            ->isRequired()
            ->validate()
            ->ifTrue(static function ($responseClass) {
                $test = is_string($responseClass);
                return !is_string($responseClass);
            })
            ->thenInvalid('response_class class must be a string.')
            ->ifTrue(static function ($responseClass) {
                return !class_exists($responseClass);
            })
            ->thenInvalid('response_class must be a valid class name (fully qualified).')
            ->end()
        ;

        return $responseClassNode;
    }
}
