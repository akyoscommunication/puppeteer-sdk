<?php

namespace Akyos\PuppeteerSDK\Service;

use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Puppeteer
{
    private $client;
    private $parameterBag;

    public function __construct(
        HttpClientInterface $client,
        ParameterBagInterface $parameterBag
    ) {
        $this->client = $client;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function download($url, $paramsUrl = [], $params = []): Response
    {
        $endpoints = $this->parameterBag->get('puppeteer_sdk.endpoints');
        $options = $this->parameterBag->get('puppeteer_sdk.options');

        $res = new Response("Aucunes urls de renseignées.", Response::HTTP_INTERNAL_SERVER_ERROR);

        foreach ($endpoints as $endpoint) {

            $res = $this->client->request('GET', $endpoint, [
                'url' => $url.'?'.http_build_query(array_merge([
                        'token' => $this->getToken(),
                    ], $paramsUrl)),
                'pdf_options' => array_merge($options, $params),
            ]);
            $statusCode = $res->getStatusCode();

            if ($statusCode === Response::HTTP_OK) {
                break;
            }
        }

        return $res;
    }

    public function getToken(): string
    {
        $key = $this->parameterBag->get('puppeteer_sdk.token.key');
        $algo = $this->parameterBag->get('puppeteer_sdk.token.algo');

        return JWT::encode([
            'date' => new \DateTime(),
        ], $key, $algo);
    }
}
