<?php

declare(strict_types=1);

namespace KoenHoeijmakers\LaravelExact;

use Illuminate\Support\Str;

class ClientConfig
{
    /**
     * The client id.
     *
     * @var integer
     */
    protected $clientId;

    /**
     * The client secret.
     *
     * @var string
     */
    protected $clientSecret;

    /**
     * The access token.
     *
     * @var string
     */
    protected $accessToken;

    /**
     * The refresh token.
     *
     * @var string
     */
    protected $refreshToken;

    /**
     * The division.
     *
     * @var int
     */
    protected $division;

    /**
     * The base url.
     *
     * @var string
     */
    protected $baseUrl = 'https://start.exactonline.nl';

    /**
     * The OAuth2 token uri.
     *
     * @var string
     */
    protected $tokenUri = '/api/oauth2/token';

    /**
     * ClientConfig constructor.
     *
     * @param  array $payload
     */
    public function __construct(array $payload = [])
    {
        $this->setConfigFromPayload($payload);
    }

    /**
     * Set the config by the given payload.
     *
     * @param  array $payload
     * @return void
     */
    protected function setConfigFromPayload(array $payload = [])
    {
        foreach ($payload as $key => $value) {
            if (empty($value)) {
                continue;
            }

            $method = $this->getSetterMethodFromKey($key);

            if (! method_exists($this, $method)) {
                continue;
            }

            $this->{$method}($value);
        }
    }

    /**
     * Get the setter method for the given key.
     *
     * @param string $key
     * @return string
     */
    protected function getSetterMethodFromKey(string $key)
    {
        return 'set' . Str::studly($key);
    }

    /**
     * Get the client secret.
     *
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Set the client secret.
     *
     * @param  mixed $clientSecret
     * @return $this
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * Get the refresh token.
     *
     * @return mixed
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * Set the refresh token.
     *
     * @param  mixed $refreshToken
     * @return $this
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * Get the access token.
     *
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set the access token.
     *
     * @param  mixed $accessToken
     * @return $this
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get the client id.
     *
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set the client id.
     *
     * @param  mixed $clientId
     * @return $this
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get the division.
     *
     * @return mixed
     */
    public function getDivision()
    {
        return $this->division;
    }

    /**
     * Set the division.
     *
     * @param  mixed $division
     * @return $this;
     */
    public function setDivision($division)
    {
        $this->division = $division;

        return $this;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(string $baseUrl): ClientConfig
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Get the token uri.
     *
     * @return string
     */
    public function getTokenUri(): string
    {
        return $this->tokenUri;
    }
}
