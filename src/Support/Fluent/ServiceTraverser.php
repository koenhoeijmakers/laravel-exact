<?php

namespace KoenHoeijmakers\LaravelExact\Support\Fluent;

use Illuminate\Support\Str;
use KoenHoeijmakers\LaravelExact\ClientInterface;

class ServiceTraverser
{
    /**
     * The resolved parts.
     *
     * @var array
     */
    protected $resolvedParts = [];

    /**
     * The client.
     *
     * @var \KoenHoeijmakers\LaravelExact\ClientInterface
     */
    protected $client;

    /**
     * ServiceReach constructor.
     *
     * @param \KoenHoeijmakers\LaravelExact\ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Dynamically handle calls to resolve.
     *
     * @param string $name
     * @param array  $arguments
     * @return \KoenHoeijmakers\LaravelExact\Services\Service|\KoenHoeijmakers\LaravelExact\Support\Fluent\ServiceTraverser
     */
    public function __call($name, array $arguments = [])
    {
        return $this->resolve($name);
    }

    /**
     * Dynamically handle calls to resolve.
     *
     * @param $name
     * @return \KoenHoeijmakers\LaravelExact\Services\Service|\KoenHoeijmakers\LaravelExact\Support\Fluent\ServiceTraverser
     */
    public function __get($name)
    {
        return $this->resolve($name);
    }

    /**
     * Resolve the given part.
     *
     * @param $name
     * @return \KoenHoeijmakers\LaravelExact\Support\Fluent\ServiceTraverser|\KoenHoeijmakers\LaravelExact\Services\Service
     */
    public function resolve($name)
    {
        $called = $this->getResolvedCall(
            $part = $this->formatPart($name)
        );

        // If the service exists, return it.
        if (class_exists($called)) {
            return new $called($this->client);
        }

        // Resolve the part and return self.
        return $this->resolvePart($part);
    }

    /**
     * Format the name.
     *
     * @param $name
     * @return string
     */
    protected function formatPart($name)
    {
        return Str::studly($name);
    }

    /**
     * Resolve the given part.
     *
     * @param $part
     * @return $this
     */
    protected function resolvePart($part)
    {
        $this->resolvedParts[] = $part;

        return $this;
    }

    /**
     * Get the resolved call.
     *
     * @param $name
     * @return string
     */
    protected function getResolvedCall($name)
    {
        return $this->getResolvedNamespace() . '\\' . $name;
    }

    /**
     * Get the resolved namespace.
     *
     * @return string
     */
    protected function getResolvedNamespace()
    {
        return $this->getServiceNamespace() . '\\' . $this->resolveParts();
    }

    /**
     * Resolve the already given parts.
     *
     * @return string
     */
    protected function resolveParts()
    {
        return implode('\\', $this->resolvedParts);
    }

    /**
     * Get the service namespace.
     *
     * @return string
     */
    protected function getServiceNamespace()
    {
        return '\\KoenHoeijmakers\\LaravelExact\\Services';
    }
}
