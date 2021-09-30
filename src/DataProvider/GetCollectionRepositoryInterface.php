<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\DataProvider;

use ApiPlatform\Core\DataProvider\PaginatorInterface;

interface GetCollectionRepositoryInterface
{
    /**
     * @param GetCollectionRequest $getCollectionRequest
     * @return iterable|PaginatorInterface
     *
     * @phpstan-ignore-next-line
     */
    public function getCollection(GetCollectionRequest $getCollectionRequest): iterable|PaginatorInterface;
}
