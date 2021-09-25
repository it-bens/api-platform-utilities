<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\Tests;

use ApiPlatform\Core\Validator\ValidatorInterface;
use ITB\ApiPlatformUtilitiesBundle\ITBApiPlatformUtilitiesBundle;
use ITB\ApiPlatformUtilitiesBundle\Tests\Mock\ApiPlatformValidatorMock;
use ITB\ObjectTransformerBundle\ITBObjectTransformerBundle;
use ITB\ObjectTransformerTestUtilities\DummyTransformer;
use ITB\ObjectTransformerTestUtilities\DummyTransformerReverse;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Kernel;

final class ITBApiPlatformUtilitiesKernel extends Kernel
{
    /** @var array $apiPlatformUtilitiesConfig */
    private $apiPlatformUtilitiesConfig;

    public function __construct(string $environment, bool $debug, $apiPlatformUtilitiesConfig = [])
    {
        parent::__construct($environment, $debug);
        $this->apiPlatformUtilitiesConfig = $apiPlatformUtilitiesConfig;
    }

    public function getCacheDir(): string
    {
        return __DIR__ . '/cache/' . spl_object_hash($this);
    }

    public function registerBundles(): array
    {
        return [
            //new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            //new \ApiPlatform\Core\Bridge\Symfony\Bundle\ApiPlatformBundle(),
            new ITBObjectTransformerBundle(),
            new ITBApiPlatformUtilitiesBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(function (ContainerBuilder $container) {
            if (!empty($this->apiPlatformUtilitiesConfig)) {
                $container->loadFromExtension('itb_api_platform_utilities', $this->apiPlatformUtilitiesConfig);
            }

            $validatorDefinition = new Definition(ApiPlatformValidatorMock::class);
            $validatorDefinition->setPublic(true);

            $container->register(ValidatorInterface::class, ApiPlatformValidatorMock::class);

            $dummyTransformerDefinition = new Definition(DummyTransformer::class);
            $dummyTransformerDefinition->setAutoconfigured(true);
            $dummyTransformerReverseDefinition = new Definition(DummyTransformerReverse::class);
            $dummyTransformerReverseDefinition->setAutoconfigured(true);

            $container->addDefinitions(
                [
                    'itb_object_transformer.dummy_transformer' => $dummyTransformerDefinition,
                    'itb_object_transformer.dummy_transformer_reverse' => $dummyTransformerReverseDefinition,
                    'api_platform.validator' => $validatorDefinition
                ]
            );
            $container->addAliases(
                [
                    ValidatorInterface::class => 'api_platform.validator'
                ]
            );
        });
    }
}