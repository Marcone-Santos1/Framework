<?php

namespace MiniRestFramework\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Exception\RequestException;

class RequestClient
{
    private Client $client;
    private string $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => 2.0,
        ]);
    }

    public function sendRequest(string $method, string $uri, array $options = [])
    {
        try {
            $guzzleRequest = new GuzzleRequest($method, $uri, $options['headers'] ?? []);
            $response = $this->client->send($guzzleRequest, $options);
            return [
                'status_code' => $response->getStatusCode(),
                'body' => json_decode($response->getBody()->getContents()),
                'headers' => $response->getHeaders()
            ];
        } catch (RequestException $e) {
            return [
                'status_code' => $e->getCode(),
                'body' => json_decode($e->getMessage()),
                'headers' => $e->getResponse() ? $e->getResponse()->getHeaders() : []
            ];
        }
    }
}
