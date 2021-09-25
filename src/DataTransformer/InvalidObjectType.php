<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\DataTransformer;

use ITB\ApiPlatformUtilitiesBundle\Exception\InvalidType;

final class InvalidObjectType extends InvalidType
{
    public static function new(string $objectClassName): InvalidObjectType
    {
        return parent::create($objectClassName, 'Object');
    }
}
