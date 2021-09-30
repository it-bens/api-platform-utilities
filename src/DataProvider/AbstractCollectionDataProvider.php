<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\PaginatorInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Exception;
use Symfony\Component\HttpFoundation\InputBag;

abstract class AbstractCollectionDataProvider implements
    ContextAwareCollectionDataProviderInterface,
    RestrictedDataProviderInterface
{
    /**
     * @return string
     */
    abstract protected static function getResourceClass(): string;

    /**
     * @param class-string $resourceClass
     * @param string|null $operationName
     * @param array $context
     * @return iterable|PaginatorInterface
     *
     * @phpstan-ignore-next-line
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable|PaginatorInterface
    {
        // The 'filters' key can be missing if no query parameter was passed.
        $searchRequest = $this->createGetCollectionRequest(new InputBag($context['filters'] ?? []));

        try {
            return $this->getResourceRepository()->getCollection($searchRequest);
        } catch (Exception $exception) {
            throw GetCollectionFailed::new($exception);
        }
    }

    /**
     * @param class-string $resourceClass
     * @param string|null $operationName
     * @param array $context
     * @return bool
     *
     * @phpstan-ignore-next-line
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return static::getResourceClass() === $resourceClass;
    }

    /**
     * @param InputBag $query
     * @return GetCollectionRequest
     *
     * @phpstan-ignore-next-line
     */
    abstract protected function createGetCollectionRequest(InputBag $query): GetCollectionRequest;

    /**
     * @return GetCollectionRepositoryInterface
     */
    abstract protected function getResourceRepository(): GetCollectionRepositoryInterface;
}
