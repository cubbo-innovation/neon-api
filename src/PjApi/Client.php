<?php

namespace Neon\PjApi;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;

class Client
{

    protected $baseUri = 'https://apiparceiros.banconeo.com.br';

    protected $isDev = true;

    protected $version = 'V1';

    protected $clientId;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    public function __construct(\GuzzleHttp\Client $httpClient, $clientId, $isDevelopment = false)
    {
        $this->isDev = $isDevelopment;

        if ($this->isDev) {
            $this->baseUri = 'https://servicosdev.neohomol.com.br';
        }

        $this->httpClient = $httpClient;
        $this->setClientId($clientId);
    }

    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;
    }

    public function setApiVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    public function post($uri, array $options = [])
    {
        return $this->request('POST', $uri, $options);
    }

    public function request($method, $uri, array $options = [])
    {
        if (empty($options['headers'])) {
            $options['headers'] = [];
        }

        $options['base_uri'] = $this->buildBaseUri();

        try {

            if ($this->isDev) {
                $uri = 'servicopj/' . $this->version . $uri;
            } else {
                $uri = $this->version . $uri;
            }

            $response = $this->httpClient->request($method, $uri, $options);

            return $this->getJson($response);

        } catch (ClientException $e) {
            throw $e;
        }

    }

    public function buildBaseUri()
    {
        return $this->baseUri;
    }

    protected function getJson(Response $response)
    {
        $json = json_decode($response->getBody()->getContents(), true);

        if (!empty($json)) {
            return $json;
        }

        return false;
    }

}
