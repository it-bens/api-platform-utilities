<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ITB\ObjectTransformer\TransformationMediatorInterface;

final class ApiOutputTransformer implements DataTransformerInterface
{
    /** @var TransformationMediatorInterface $transformationMediator */
    private $transformationMediator;
    /** @var array $transformations */
    private $transformations;

    /**
     * @param array $transformations
     * @param TransformationMediatorInterface $transformationMediator
     */
    public function __construct(array $transformations, TransformationMediatorInterface $transformationMediator) {
        foreach ($transformations as $transformation) {
            if (!array_key_exists('object_class', $transformation)) {
                throw InvalidObjectType::new('null');
            }
            if (!class_exists($transformation['object_class'])) {
                throw InvalidObjectType::new($transformation['object_class']);
            }

            if (!array_key_exists('response_class', $transformation)) {
                throw InvalidResponseType::new('null');
            }
            if (!class_exists($transformation['response_class'])) {
                throw InvalidResponseType::new($transformation['response_class']);
            }
        }

        $this->transformations = $transformations;
        $this->transformationMediator = $transformationMediator;
    }

    /**
     * @param array|object $data
     * @param string $to
     * @param array $context
     * @return bool
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        foreach ($this->transformations as $transformation) {
            $object = $transformation['object_class'];
            $response = $transformation['response_class'];
            if ($object === $context['input']['class'] && $response === $to && is_a($data, $object)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param object $object
     * @param string $to
     * @param array $context
     * @return object
     */
    public function transform($object, string $to, array $context = [])
    {
        return $this->transformationMediator->transform($object, $to);
    }
}