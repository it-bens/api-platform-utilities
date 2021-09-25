<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use ITB\ObjectTransformer\TransformationMediatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class ApiInputTransformer implements DataTransformerInterface
{
    /** @var ValidatorInterface $validator */
    protected $validator;
    /** @var TransformationMediatorInterface $transformationMediator */
    private $transformationMediator;
    /** @var array $transformations */
    private $transformations;

    /**
     * @param array $transformations
     * @param TransformationMediatorInterface $transformationMediator
     * @param ValidatorInterface $validator
     */
    public function __construct(
        array $transformations,
        TransformationMediatorInterface $transformationMediator,
        ValidatorInterface $validator
    ) {
        foreach ($transformations as $transformation) {
            if (!array_key_exists('request_class', $transformation)) {
                throw InvalidRequestType::new('null');
            }
            if (!class_exists($transformation['request_class'])) {
                throw InvalidRequestType::new($transformation['request_class']);
            }

            if (!array_key_exists('object_class', $transformation)) {
                throw InvalidObjectType::new('null');
            }
            if (!class_exists($transformation['object_class'])) {
                throw InvalidObjectType::new($transformation['object_class']);
            }
        }

        $this->transformations = $transformations;
        $this->transformationMediator = $transformationMediator;
        $this->validator = $validator;
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
            $request = $transformation['request_class'];
            $object = $transformation['object_class'];

            if ($request === $context['input']['class'] && $object === $to && !is_a($data, $object)) {
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
    public function transform($object, string $to, array $context = []): object
    {
        // This is method throws if the validation is unsuccessful.
        $this->validator->validate($object);

        if (array_key_exists(AbstractNormalizer::OBJECT_TO_POPULATE, $context)) {
            $persistedObject = $context[AbstractNormalizer::OBJECT_TO_POPULATE];

            return $this->transformationMediator->transform($object, $to, [$persistedObject]);
        }

        return $this->transformationMediator->transform($object, $to);
    }
}