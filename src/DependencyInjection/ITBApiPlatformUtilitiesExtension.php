<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\DependencyInjection;

use ApiPlatform\Core\Validator\ValidatorInterface;
use ITB\ApiPlatformUtilitiesBundle\DataTransformer\ApiInputTransformer;
use ITB\ObjectTransformer\TransformationMediatorInterface;
use ITB\ObjectTransformer\TransformerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

final class ITBApiPlatformUtilitiesExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $inputTransformerDefinition = $container->getDefinition('itb_api_platform_utilities.api_input_transformer');
        $inputTransformerDefinition->setArgument(0, $config['input_transformations']);
        $inputTransformerDefinition->setArgument(1, new Reference('itb_object_transformer.transformation_mediator'));
        $inputTransformerDefinition->setArgument(2, new Reference(ValidatorInterface::class));

        $outputTransformerDefinition = $container->getDefinition('itb_api_platform_utilities.api_output_transformer');
        $outputTransformerDefinition->setArgument(0, $config['output_transformations']);
        $outputTransformerDefinition->setArgument(1, new Reference('itb_object_transformer.transformation_mediator'));
    }

    public function getAlias(): string
    {
        return 'itb_api_platform_utilities';
    }
}