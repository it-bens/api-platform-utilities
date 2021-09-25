<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\DataTransformer;

use ITB\ApiPlatformUtilitiesBundle\Exception\InvalidType;

final class InvalidRequestType extends InvalidType
{
    public static function new(string $requestClassName): self
    {
        return parent::create($requestClassName, 'Request');
    }
}
