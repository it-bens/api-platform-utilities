<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\Tests;

use ApiPlatform\Core\Validator\ValidatorInterface;
use Exception;
use ITB\ApiPlatformUtilitiesBundle\ITBApiPlatformUtilitiesBundle;
use ITB\ApiPlatformUtilitiesBundle\Tests\Mock\ApiPlatformValidatorMock;
use ITB\ApiPlatformUtilitiesBundle\Tests\Mock\DummyTransformer;
use ITB\ApiPlatformUtilitiesBundle\Tests\Mock\DummyTransformerReverse;
use ITB\ObjectTransformerBundle\ITBObjectTransformerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

final class ITBApiPlatformUtilitiesKernel extends Kernel
{
    /** @var array{"input_transformations": array, "output_transformations": array}|null $apiPlatformUtilitiesConfig */
    private ?array $apiPlatformUtilitiesConfig;

    /**
     * @param string $environment
     * @param bool $debug
     * @param array{"input_transformations": array, "output_transformations": array}|null $apiPlatformUtilitiesConfig
     */
    public function __construct(string $environment, bool $debug, ?array $apiPlatformUtilitiesConfig = null)
    {
        parent::__construct($environment, $debug);
        $this->apiPlatformUtilitiesConfig = $apiPlatformUtilitiesConfig;
    }

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        return __DIR__ . '/cache/' . spl_object_hash($this);
    }

    /**
     * @return BundleInterface[]
     */
    public function registerBundles(): array
    {
        return [
            new ITBObjectTransformerBundle(),
            new ITBApiPlatformUtilitiesBundle(),
        ];
    }

    /**
     * @param LoaderInterface $loader
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(function (ContainerBuilder $container) {
            if (null !== $this->apiPlatformUtilitiesConfig) {
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
