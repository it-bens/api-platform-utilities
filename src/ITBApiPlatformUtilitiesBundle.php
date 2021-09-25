<?php

declare(strict_types=1);

namespace ITB\ApiPlatformUtilitiesBundle;

use ITB\ApiPlatformUtilitiesBundle\DependencyInjection\ITBApiPlatformUtilitiesExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ITBApiPlatformUtilitiesBundle extends Bundle
{
    /**
     * Overridden to allow for the custom extension alias.
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new ITBApiPlatformUtilitiesExtension();
        }

        return $this->extension;
    }
}