<?php

namespace AppBundle\Traits;

use GuzzleHttp\Client;

trait GuzzleHttpClientTrait
{
    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @param Client $httpClient
     * @return $this
     */
    public function setHttpClient(Client $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }
}