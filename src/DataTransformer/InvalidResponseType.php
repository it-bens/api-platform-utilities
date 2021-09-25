<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\DataTransformer;

use ITB\ApiPlatformUtilitiesBundle\Exception\InvalidType;

final class InvalidResponseType extends InvalidType
{
    public static function new(string $responseClassName): InvalidResponseType
    {
        return parent::create($responseClassName, 'Response');
    }
}
