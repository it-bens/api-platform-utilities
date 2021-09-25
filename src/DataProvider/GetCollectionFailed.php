<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\DataProvider;

use Exception;
use RuntimeException;

final class GetCollectionFailed extends RuntimeException
{
    public static function new(Exception $exception): self
    {
        return new self('The collection search failed with an exception.', 0, $exception);
    }
}