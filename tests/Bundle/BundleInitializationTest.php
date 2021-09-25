<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\Tests\Bundle;

use ITB\ApiPlatformUtilitiesBundle\DataTransformer\ApiInputTransformer;
use ITB\ApiPlatformUtilitiesBundle\DataTransformer\ApiOutputTransformer;
use ITB\ApiPlatformUtilitiesBundle\Tests\ITBApiPlatformUtilitiesKernel;
use PHPUnit\Framework\TestCase;

final class BundleInitializationTest extends TestCase
{
    public function testRegisterBundle(): void
    {
        $kernel = new ITBApiPlatformUtilitiesKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();

        $apiInputTransformer = $container->get('itb_api_platform_utilities.api_input_transformer');
        $this->assertInstanceOf(ApiInputTransformer::class, $apiInputTransformer);
        $apiOutputTransformer = $container->get('itb_api_platform_utilities.api_output_transformer');
        $this->assertInstanceOf(ApiOutputTransformer::class, $apiOutputTransformer);
    }
}