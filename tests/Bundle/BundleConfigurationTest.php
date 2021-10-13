<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\Tests\Bundle;

use ITB\ApiPlatformUtilitiesBundle\DataTransformer\ApiInputTransformer;
use ITB\ApiPlatformUtilitiesBundle\DataTransformer\ApiOutputTransformer;
use ITB\ApiPlatformUtilitiesBundle\Tests\ITBApiPlatformUtilitiesKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Yaml\Yaml;

final class BundleConfigurationTest extends TestCase
{
    /**
     * @return array<string, array<string>>
     */
    public function provideBundleConfigurationsInvalid(): array
    {
        return [
            'input_transformation_without_request_class' => [__DIR__ . '/../Fixtures/BundleConfiguration/config_invalid_input_transformations_1.yml'],
            'input_transformation_with_invalid_request_class' => [__DIR__ . '/../Fixtures/BundleConfiguration/config_invalid_input_transformations_2.yml'],
            'input_transformation_without_object_class' => [__DIR__ . '/../Fixtures/BundleConfiguration/config_invalid_input_transformations_3.yml'],
            'input_transformation_with_invalid_object_class' => [__DIR__ . '/../Fixtures/BundleConfiguration/config_invalid_input_transformations_4.yml'],
            'output_transformation_without_object_class' => [__DIR__ . '/../Fixtures/BundleConfiguration/config_invalid_output_transformations_1.yml'],
            'output_transformation_with_invalid_object_class' => [__DIR__ . '/../Fixtures/BundleConfiguration/config_invalid_output_transformations_2.yml'],
            'output_transformation_without_response_class' => [__DIR__ . '/../Fixtures/BundleConfiguration/config_invalid_output_transformations_3.yml'],
            'output_transformation_with_invalid_response_class' => [__DIR__ . '/../Fixtures/BundleConfiguration/config_invalid_output_transformations_4.yml'],
        ];
    }

    /**
     * @dataProvider provideBundleConfigurationsInvalid
     *
     * @param string $configFile
     */
    public function testBundleWithConfigurationInvalid(string $configFile): void
    {
        $this->setOutputCallback(static function () {
        });
        $this->expectException(InvalidConfigurationException::class);

        $config = Yaml::parseFile($configFile);
        $kernel = new ITBApiPlatformUtilitiesKernel('test', true, $config);
        $kernel->boot();
    }

    public function testBundleWithConfigurationValid(): void
    {
        $config = Yaml::parseFile(__DIR__ . '/../Fixtures/BundleConfiguration/config_valid.yml');
        $kernel = new ITBApiPlatformUtilitiesKernel('test', true, $config);
        $kernel->boot();
        $container = $kernel->getContainer();

        $apiInputTransformer = $container->get('itb_api_platform_utilities.api_input_transformer');
        $this->assertInstanceOf(ApiInputTransformer::class, $apiInputTransformer);

        $apiInputTransformer = $container->get('itb_api_platform_utilities.api_output_transformer');
        $this->assertInstanceOf(ApiOutputTransformer::class, $apiInputTransformer);
    }
}
