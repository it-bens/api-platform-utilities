<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\DataProvider;

use ApiPlatform\Core\DataProvider\PaginatorInterface;

interface GetCollectionRepositoryInterface
{
    /**
     * @param $getCollectionRequest
     * @return iterable|PaginatorInterface
     */
    public function getCollection($getCollectionRequest);
}