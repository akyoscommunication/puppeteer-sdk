<?php

namespace Akyos\PuppeteerSDK\Service;

use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\Response\TraceableResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Puppeteer
{
    private $client;
    private $container;

    public function __construct(
        HttpClientInterface $client,
        ContainerInterface $container
    ) {
        $this->client = $client;
        $this->container = $container;
    }

    protected function fetch($url, $paramsUrl = [], $params = [])
    {
        $endpoints = $this->container->getParameter('endpoints');
        $options = $this->container->getParameter('options');

        foreach ($endpoints as $endpoint) {
            $res = $this->client->request('GET', $endpoint, [
                'query' => [
                    'url' => $url.'?'.http_build_query(array_merge([
                            'token' => $this->getToken(),
                        ], $paramsUrl)),
                    'pdf_options' => array_merge($options, $params),
                ],
            ]);
            $statusCode = $res->getStatusCode();

            if ($statusCode === Response::HTTP_OK) {
                break;
            }
        }

        return $res;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function download($filename, $url, $paramsUrl = [], $params = []): Response
    {
        $fetch = $this->fetch($url, $paramsUrl, $params);
        $response = $this->getResponse($fetch);
        $response->headers->set('Content-Disposition', 'attachment;filename=' . $filename);

        return $response;
    }

    public function open($filename, $url, $paramsUrl = [], $params = []): Response
    {
        $fetch = $this->fetch($url, $paramsUrl, $params);
        return $this->getResponse($fetch);
    }

    public function save($output, $url, $paramsUrl = [], $params = []): File
    {
        $filesystem = new Filesystem();
        $fetch = $this->fetch($url, $paramsUrl, $params);

        $filesystem->dumpFile($output, $fetch->getContent());

        return new File($output);
    }

    protected function getResponse($fetch): Response
    {
        $response = new Response($fetch->getContent());
        $response->setStatusCode($fetch->getStatusCode());

        foreach ($fetch->getHeaders() as $k => $v) {
            $response->headers->set($k, $v);
        }

        return $response;
    }

    public function getToken(): string
    {
        $token = $this->container->getParameter('token');

        return JWT::encode([
            'date' => new \DateTime(),
        ], $token['key'], $token['algo']);
    }
}
