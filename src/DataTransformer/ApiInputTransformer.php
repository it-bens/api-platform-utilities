<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use ITB\ObjectTransformer\TransformationEnvelope;
use ITB\ObjectTransformer\TransformationMediatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class ApiInputTransformer implements DataTransformerInterface
{
    /**
     * @param array<array{"request_class": class-string, "object_class": class-string}> $transformations
     * @param TransformationMediatorInterface $transformationMediator
     * @param ValidatorInterface $validator
     */
    public function __construct(
        private array $transformations,
        private TransformationMediatorInterface $transformationMediator,
        private ValidatorInterface $validator
    ) {
        foreach ($this->transformations as $transformation) {
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
    }

    /**
     * @param object $data
     * @param string $to
     * @param array $context
     * @return bool
     *
     * @phpstan-ignore-next-line
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
     *
     * @phpstan-ignore-next-line
     */
    public function transform($object, string $to, array $context = []): object
    {
        // This is method throws if the validation is unsuccessful.
        $this->validator->validate($object);

        if (array_key_exists(AbstractNormalizer::OBJECT_TO_POPULATE, $context)) {
            $persistedObject = $context[AbstractNormalizer::OBJECT_TO_POPULATE];
            $envelope = new TransformationEnvelope($object, [new PersistedObjectStamp($persistedObject)]);

            return $this->transformationMediator->transform($envelope, $to);
        }

        return $this->transformationMediator->transform($object, $to);
    }
}
