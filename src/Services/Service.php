<?php

namespace KoenHoeijmakers\LaravelExact\Services;

use GuzzleHttp\Client;
use JsonSerializable;

abstract class Service implements JsonSerializable
{
    /**
     * The resource uri.
     *
     * @var string
     */
    protected $resourceUri;

    /**
     * The client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The model's fillable attributes.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be casted to their native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Model constructor.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Fill the model with the given attributes.
     *
     * @param array $attributes
     * @return $this
     */
    public function fill($attributes = [])
    {
        $this->fillableFromArray($attributes);

        return $this;
    }

    /**
     * Save the model to exact.
     *
     * @return bool
     */
    public function save()
    {
        return true;
    }

    /**
     * Update the current model.
     *
     * @param array $attributes
     * @return bool
     */
    public function update($attributes = [])
    {
        return $this->fill($attributes)->save();
    }

    /**
     * Check if the given attribute is fillable.
     *
     * @param $attribute
     * @return bool
     */
    public function isFillable($attribute)
    {
        return in_array($attribute, $this->getFillable());
    }

    /**
     * Get the fillable attributes.
     *
     * @return array
     */
    public function getFillable()
    {
        return $this->fillable;
    }

    /**
     * Fill the given attributes, if possible.
     *
     * @param array $attributes
     */
    protected function fillableFromArray($attributes = [])
    {
        foreach ($attributes as $attribute => $value) {
            if ($this->isFillable($attribute)) {
                $this->setAttribute($attribute, $value);
            }
        }
    }

    /**
     * Whether the model has fillables.
     *
     * @return bool
     */
    protected function hasFillable()
    {
        return !empty($this->getFillable());
    }

    /**
     * Get all of the model's attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get the given attribute.
     *
     * @param $attribute
     * @return mixed
     */
    public function getAttribute($attribute)
    {
        return $this->attributes[$attribute];
    }

    /**
     * Set the given attribute.
     *
     * @param $attribute
     * @param $value
     * @return void
     */
    public function setAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
    }

    /**
     * Getter.
     *
     * @param $attribute
     * @return mixed
     */
    public function __get($attribute)
    {
        return $this->getAttribute($attribute);
    }

    /**
     * Setter.
     *
     * @param $attribute
     * @param $value
     * @return void
     */
    public function __set($attribute, $value)
    {
        $this->setAttribute($attribute, $value);
    }

    /**
     * Get the resource uri.
     *
     * @return string
     */
    public function getResourceUri()
    {
        return $this->resourceUri;
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

    /**
     * Specify data which should be serialized to JSON
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->getAttributes();
    }
}
