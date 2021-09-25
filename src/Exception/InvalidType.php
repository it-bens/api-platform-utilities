<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\Exception;

use InvalidArgumentException;

abstract class InvalidType extends InvalidArgumentException
{
    protected static function create(string $className, string $testedType)
    {
        return new static(sprintf('\'%s\' is not a valid %s type.', $className, $testedType), 0, null);
    }
}
