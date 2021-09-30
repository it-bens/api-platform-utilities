<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\DataTransformer;

use ITB\ObjectTransformer\Stamp\TransformationStampInterface;

final class PersistedObjectStamp implements TransformationStampInterface
{
    /**
     * @param object $persistedObject
     * @param int $priority
     */
    public function __construct(private object $persistedObject, private int $priority = 0)
    {
    }

    /**
     * @return object
     */
    public function getPersistedObject(): object
    {
        return $this->persistedObject;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
