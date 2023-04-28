<?php

namespace Akyos\PuppeteerSDK;

use Akyos\PuppeteerSDK\DependencyInjection\PuppeteerSKDExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PuppeteerSDK extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new PuppeteerSKDExtension();
        }
        return $this->extension;
    }
}