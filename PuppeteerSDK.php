<?php

namespace Akyos\PuppeteerSDK;

use Akyos\PuppeteerSDK\DependencyInjection\PuppeteerSDKExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PuppeteerSDK extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new PuppeteerSDKExtension();
        }
        return $this->extension;
    }
}