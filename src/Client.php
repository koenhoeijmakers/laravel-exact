<?php

namespace KoenHoeijmakers\LaravelExact;

use GuzzleHttp\ClientInterface as HttpInterface;

class Client implements ClientInterface
{
    /**
     * Exact Client Version.
     */
    const VERSION = '0.1.0';

    /**
     * The client for talking to the api.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * The client config.
     *
     * @var \KoenHoeijmakers\LaravelExact\ClientConfig
     */
    protected $clientConfig;

    /**
     * Exact constructor.
     *
     * @param \GuzzleHttp\ClientInterface                $client
     * @param \KoenHoeijmakers\LaravelExact\ClientConfig $clientConfig
     */
    public function __construct(HttpInterface $client, ClientConfig $clientConfig)
    {
        $this->setClient($client);
        $this->setClientConfig($clientConfig);
    }

    /**
     * Get the client.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set the client.
     *
     * @param \GuzzleHttp\ClientInterface $client
     * @return $this
     */
    public function setClient(HttpInterface $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Set the config.
     *
     * @param \KoenHoeijmakers\LaravelExact\ClientConfig $clientConfig
     * @return $this
     */
    public function setClientConfig(ClientConfig $clientConfig)
    {
        $this->clientConfig = $clientConfig;

        return $this;
    }
}
