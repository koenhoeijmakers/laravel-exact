<?php

namespace KoenHoeijmakers\LaravelExact;

use GuzzleHttp\Client;

class Exact
{
    /**
     * The client for talking to the api.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Exact constructor.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the client.
     *
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
