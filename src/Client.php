<?php

namespace KoenHoeijmakers\LaravelExact;

use GuzzleHttp\ClientInterface as HttpInterface;
use Illuminate\Support\Str;
use KoenHoeijmakers\LaravelExact\Support\Fluent\ServiceTraverser;

class Client implements ClientInterface
{
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

    /**
     * Get the endpoint url (for the given uri).
     *
     * @param $uri
     * @return string
     */
    public function getEndpointUrl($uri)
    {
        $config = $this->getClientConfig();

        if (Str::contains($uri, '{division}')) {
            $uri = str_replace('{division}', $config->getDivision(), $uri);
        }

        if (Str::endsWith($baseUrl = $config->getBaseUrl(), '/')) {
            return $baseUrl . $uri;
        }

        return $baseUrl . '/' . $uri;
    }

    /**
     * Get the client config.
     *
     * @return \KoenHoeijmakers\LaravelExact\ClientConfig
     */
    public function getClientConfig()
    {
        return $this->clientConfig;
    }

    /**
     * Dynamically handle calls to services.
     *
     * @param $name
     * @return \KoenHoeijmakers\LaravelExact\Services\Service|\KoenHoeijmakers\LaravelExact\Support\Fluent\ServiceTraverser
     */
    public function __get($name)
    {
        return $this->getServiceTraverser()->resolve($name);
    }

    /**
     * Dynamically handle calls to services.
     *
     * @param       $name
     * @param array $arguments
     * @return \KoenHoeijmakers\LaravelExact\Services\Service|\KoenHoeijmakers\LaravelExact\Support\Fluent\ServiceTraverser
     */
    public function __call($name, array $arguments =[])
    {
        return $this->getServiceTraverser()->resolve($name);
    }

    /**
     * Get a new instance of the service traverser.
     *
     * @return \KoenHoeijmakers\LaravelExact\Support\Fluent\ServiceTraverser
     */
    protected function getServiceTraverser()
    {
        return new ServiceTraverser($this);
    }
}
