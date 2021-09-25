<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\Tests\DataTransformer;

use ApiPlatform\Core\Validator\ValidatorInterface;
use ITB\ApiPlatformUtilitiesBundle\DataTransformer\ApiInputTransformer;
use ITB\ApiPlatformUtilitiesBundle\DataTransformer\InvalidObjectType;
use ITB\ApiPlatformUtilitiesBundle\DataTransformer\InvalidRequestType;
use ITB\ApiPlatformUtilitiesBundle\Tests\ITBApiPlatformUtilitiesKernel;
use ITB\ObjectTransformer\TransformationMediatorInterface;
use ITB\ObjectTransformerTestUtilities\Object1;
use ITB\ObjectTransformerTestUtilities\Object2;
use ITB\ObjectTransformerTestUtilities\Object3;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

final class ApiInputTransformerTest extends TestCase
{
    private const INVALID_CONFIGURATION_FILES = [
        'input_transformation_without_request_class' => __DIR__ . '/../Fixtures/BundleConfiguration/config_invalid_input_transformations_1.yml',
        'input_transformation_with_invalid_request_class' => __DIR__ . '/../Fixtures/BundleConfiguration/config_invalid_input_transformations_2.yml',
        'input_transformation_without_object_class' => __DIR__ . '/../Fixtures/BundleConfiguration/config_invalid_input_transformations_3.yml',
        'input_transformation_with_invalid_object_class' => __DIR__ . '/../Fixtures/BundleConfiguration/config_invalid_input_transformations_4.yml'
    ];

    /** @var ApiInputTransformer $apiInputTransformer */
    private $apiInputTransformer;
    /** @var TransformationMediatorInterface $transformationMediator */
    private $transformationMediator;
    /** @var ValidatorInterface $validator */
    private $validator;

    public function setUp(): void
    {
        $config = Yaml::parseFile(__DIR__ . '/../Fixtures/BundleConfiguration/config_valid.yml');
        $kernel = new ITBApiPlatformUtilitiesKernel('test', true, $config);
        $kernel->boot();
        $container = $kernel->getContainer();

        $this->apiInputTransformer = $container->get('itb_api_platform_utilities.api_input_transformer');
        $this->transformationMediator = $container->get('itb_object_transformer.transformation_mediator');
        $this->validator = $container->get('api_platform.validator');
    }

    public function testConstructionInvalidTransformationWithInvalidObjectClass(): void
    {
        $this->setOutputCallback(static function () { });

        $config = Yaml::parseFile(self::INVALID_CONFIGURATION_FILES['input_transformation_with_invalid_object_class']);
        $this->expectExceptionObject(new InvalidObjectType('\'Blub.\' is not a valid Object type.'));

        new ApiInputTransformer($config['input_transformations'], $this->transformationMediator, $this->validator);
    }

    public function testConstructionInvalidTransformationWithInvalidRequestClass(): void
    {
        $this->setOutputCallback(static function () { });

        $config = Yaml::parseFile(self::INVALID_CONFIGURATION_FILES['input_transformation_with_invalid_request_class']);
        $this->expectExceptionObject(new InvalidRequestType('\'Blub.\' is not a valid Request type.'));

        new ApiInputTransformer($config['input_transformations'], $this->transformationMediator, $this->validator);
    }

    public function testConstructionInvalidTransformationWithoutObjectClass(): void
    {
        $this->setOutputCallback(static function () { });

        $config = Yaml::parseFile(self::INVALID_CONFIGURATION_FILES['input_transformation_without_object_class']);
        $this->expectExceptionObject(new InvalidObjectType('\'null\' is not a valid Object type.'));

        new ApiInputTransformer($config['input_transformations'], $this->transformationMediator, $this->validator);
    }

    public function testConstructionInvalidTransformationWithoutRequestClass(): void
    {
        $this->setOutputCallback(static function () { });

        $config = Yaml::parseFile(self::INVALID_CONFIGURATION_FILES['input_transformation_without_request_class']);
        $this->expectExceptionObject(new InvalidRequestType('\'null\' is not a valid Request type.'));

        new ApiInputTransformer($config['input_transformations'], $this->transformationMediator, $this->validator);
    }

    public function testSupportsTransformation(): void
    {
        $request = new Object1('I\'ll be back!');
        $context['input']['class'] = Object1::class;

        $supportsTransformation = $this->apiInputTransformer->supportsTransformation(
            $request,
            Object2::class,
            $context
        );
        $this->assertTrue($supportsTransformation);
    }

    public function testSupportsTransformationNot(): void
    {
        $request = new Object3('I\'ll be back!');
        $context['input']['class'] = Object3::class;

        $supportsTransformation = $this->apiInputTransformer->supportsTransformation(
            $request,
            Object2::class,
            $context
        );
        $this->assertFalse($supportsTransformation);
    }

    public function testTransform(): void
    {
        $request = new Object1('I\'ll be back!');
        $context = [];

        $object = $this->apiInputTransformer->transform($request, Object2::class, $context);
        $this->assertInstanceOf(Object2::class, $object);
    }
}