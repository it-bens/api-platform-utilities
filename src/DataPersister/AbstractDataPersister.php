<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

abstract class AbstractDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @param array $context
     * @return bool
     *
     * @phpstan-ignore-next-line
     */
    protected function isCreateOperation(array $context): bool
    {
        return isset($context['collection_operation_name']) && 'post' === $context['collection_operation_name'];
    }

    /**
     * @param array $context
     * @return bool
     *
     * @phpstan-ignore-next-line
     */
    protected function isUpdateOperation(array $context): bool
    {
        return isset($context['collection_operation_name']) && 'put' === $context['item_operation_name'];
    }
}
