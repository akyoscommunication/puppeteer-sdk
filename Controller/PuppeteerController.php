<?php

namespace Akyos\PuppeteerSDK\Controller;

use Akyos\PuppeteerSDK\Service\Puppeteer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/_puppeteer', name: 'puppeteer_')]
#[IsGranted("CAN_PUPPETEER_ACCESS_URL")]
class PuppeteerController extends AbstractController
{

	#[Route('/show', name: 'show', methods: ['GET'])]
	public function show(Puppeteer $puppeteer, ContainerInterface $container, Request $request): Response
	{
		$filename = $request->get('filename');
		$url = $request->get('url');
		$paramsUrl = $request->get('paramsUrl');
		$params = $request->get('params');

		return $puppeteer->open($filename, $url, $paramsUrl ?? [], $params ?? []);
	}

	#[Route('/download', name: 'download', methods: ['GET'])]
	public function download(Puppeteer $puppeteer, ContainerInterface $container, Request $request): Response
	{
		$filename = $request->get('filename');
		$url = $request->get('url');
		$paramsUrl = $request->get('paramsUrl');
		$params = $request->get('params');

		return $puppeteer->download($filename, $url, $paramsUrl ?? [], $params ?? []);
	}
}
