<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\PaginatorInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Exception;
use ITB\ApiPlatformUtilitiesBundle\Dto\GetCollectionRepositoryInterface;
use Symfony\Component\HttpFoundation\InputBag;

abstract class AbstractCollectionDataProvider implements ContextAwareCollectionDataProviderInterface,
                                                         RestrictedDataProviderInterface
{
    /**
     * @return string
     */
    abstract protected static function getResourceClass(): string;

    /**
     * @param string $resourceClass
     * @param string|null $operationName
     * @param array $context
     * @return iterable|PaginatorInterface
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        // The 'filters' key can be missing if no query parameter was passed.
        $searchRequest = $this->createSearchRequest(new InputBag($context['filters'] ?? []));

        try {
            return $this->getResourceRepository()->getCollection($searchRequest);
        } catch (Exception $exception) {
            throw GetCollectionFailed::new($exception);
        }
    }

    /**
     * @param string $resourceClass
     * @param string|null $operationName
     * @param array $context
     * @return bool
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return static::getResourceClass() === $resourceClass;
    }

    /**
     * @param InputBag $query
     * @return mixed
     */
    abstract protected function createSearchRequest(InputBag $query);

    /**
     * @return GetCollectionRepositoryInterface
     */
    abstract protected function getResourceRepository(): GetCollectionRepositoryInterface;
}