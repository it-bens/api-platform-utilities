<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\Exception;

use InvalidArgumentException;

abstract class InvalidType extends InvalidArgumentException
{
    protected static function createMessage(string $className, string $testedType): string
    {
        return sprintf('\'%s\' is not a valid %s type.', $className, $testedType);
    }
}
