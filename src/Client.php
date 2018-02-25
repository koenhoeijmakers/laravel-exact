<?php

namespace KoenHoeijmakers\LaravelExact;

use GuzzleHttp\ClientInterface as HttpInterface;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use KoenHoeijmakers\LaravelExact\Exceptions\ClientException;
use KoenHoeijmakers\LaravelExact\Support\Fluent\ServiceTraverser;
use Psr\Http\Message\ResponseInterface;

class Client implements ClientInterface
{
    /**
     * The client for talking to the api.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

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
        $this->setHttpClient($client);
        $this->setClientConfig($clientConfig);
    }

    /**
     * Send a GET request.
     *
     * @param string $uri
     * @param array  $query
     * @param array  $headers
     * @return array
     */
    public function get(string $uri, array $query = [], array $headers = [])
    {
        return $this->request(
            $this->buildRequest(__FUNCTION__, $uri, null, $query, $headers)
        );
    }

    /**
     * Send a POST request.
     *
     * @param string $uri
     * @param array  $data
     * @param array  $headers
     * @return array
     */
    public function post(string $uri, array $data = [], array $headers = [])
    {
        return $this->request(
            $this->buildRequest(__FUNCTION__, $uri, null, $data, $headers)
        );
    }

    /**
     * Send a PUT request.
     *
     * @param string $uri
     * @param array  $data
     * @param array  $headers
     * @return array
     */
    public function put(string $uri, array $data = [], array $headers = [])
    {
        return $this->request(
            $this->buildRequest(__FUNCTION__, $uri, null, $data, $headers)
        );
    }

    /**
     * Send a DELETE request.
     *
     * @param string $uri
     * @param array  $headers
     * @return array
     */
    public function delete(string $uri, array $headers = [])
    {
        return $this->request(
            $this->buildRequest(__FUNCTION__, $uri, null, [], $headers)
        );
    }

    /**
     * Send the request and get a parsed response.
     *
     * @param \GuzzleHttp\Psr7\Request $request
     * @param bool                     $retry
     * @return array
     * @throws \KoenHoeijmakers\LaravelExact\Exceptions\ClientException
     */
    protected function request(Request $request, $retry = true)
    {
        try {
            return $this->parseResponse(
                $this->getHttpClient()->send($request)
            );
        } catch (\Exception $exception) {
            if ($retry && $exception->getCode() === 401) {
                $this->retrieveAccessToken();

                $this->request($request, false);
            }

            throw new ClientException($exception->getMessage());
        }
    }

    /**
     * Parse the response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return array
     */
    protected function parseResponse(ResponseInterface $response)
    {
        $data = json_decode($response->getBody()->getContents(), true);

        if (array_key_exists('d', $data)) {
            if (array_key_exists('results', $data['d'])) {
                return $data['d']['results'];
            }

            return $data['d'];
        }

        return $data;
    }

    /**
     * Build a request.
     *
     * @param            $method
     * @param            $uri
     * @param array|null $data
     * @param array      $query
     * @param array      $headers
     * @return \GuzzleHttp\Psr7\Request
     */
    protected function buildRequest($method, $uri, array $data = null, array $query = [], array $headers = [])
    {
        $headers = array_merge($headers, [
            'Accept'         => 'application/json',
            'Content-Type'   => 'application/json',
            'Authentication' => 'Bearer ' . $this->getClientConfig()->getAccessToken(),
        ]);

        $uri = $this->getEndpointUrl($uri);

        if (!empty($query)) {
            $uri .= '?' . http_build_query($query);
        }

        return new Request($method, $uri, $headers, $data);
    }

    /**
     * Retrieve the access token.
     *
     * @return string
     */
    public function retrieveAccessToken()
    {
        $config = $this->getClientConfig();

        $params = [
            'refresh_token' => $config->getRefreshToken(),
            'grant_type'    => 'refresh_token',
            'client_id'     => $config->getClientId(),
            'client_secret' => $config->getClientSecret(),
        ];

        $config->setAccessToken(
            $token = Arr::get($this->post($config->getTokenUri(), $params), 'access_token')
        );

        return $token;
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

        return $config->getBaseUrl() . $uri;
    }

    /**
     * Get the client.
     *
     * @return \GuzzleHttp\ClientInterface|\GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Set the client.
     *
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @return $this
     */
    public function setHttpClient(HttpInterface $httpClient)
    {
        $this->httpClient = $httpClient;

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
    public function __call($name, array $arguments = [])
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
