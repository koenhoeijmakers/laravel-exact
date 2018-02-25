<?php

namespace KoenHoeijmakers\LaravelExact;

interface ClientInterface
{
    /**
     * Get the endpoint url (for the given uri).
     *
     * @param $uri
     * @return string
     */
    public function getEndpointUrl($uri);
}
