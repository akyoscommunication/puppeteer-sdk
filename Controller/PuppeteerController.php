<?php

namespace Akyos\PuppeteerSDK\Controller;

use Akyos\PuppeteerSDK\Service\Puppeteer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/_puppeteer", name="puppeteer_")
 * @return Response
 */
class PuppeteerController extends AbstractController
{
    /**
     * @Route("/show", name="show", methods={"GET"})
     * @param Puppeteer $puppeteer
     * @param string $filename
     * @param string $url
     * @param array $paramsUrl
     * @param array|null $params
     * @return Response
     */
    public function show(Puppeteer $puppeteer, string $filename, string $url, array $paramsUrl = [], ?array $params = []): Response
    {
        return $puppeteer->open($filename, $url, $paramsUrl, $params);
    }
}
