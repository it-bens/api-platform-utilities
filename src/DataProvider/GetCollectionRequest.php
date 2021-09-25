<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\DataProvider;

abstract class GetCollectionRequest
{
    /** @var int $itemsPerPage */
    public $itemsPerPage = 20;
    /** @var int $page */
    public $page = 1;
    /** @var bool $pagination */
    public $pagination = true;
}