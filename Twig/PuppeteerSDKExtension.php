<?php

namespace Akyos\PuppeteerSDK\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PuppeteerSDKExtension extends AbstractExtension
{
    private $urlGenerator;
    private $container;

    public function __construct(UrlGeneratorInterface $urlGenerator, ContainerInterface $container)
    {
        $this->urlGenerator = $urlGenerator;
        $this->container = $container;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('puppeteer_sdk_path', [$this, 'puppeteerSDKPath']),
        ];
    }

    public function puppeteerSDKPath($action, $filename, $name, $parameters = [])
    {
        return $this->urlGenerator->generate("puppeteer_$action", [
            'token' => $this->container->getParameter('token')['key'],
            'filename' => $filename,
            'url' => $this->urlGenerator->generate($name, $parameters, UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }
}
