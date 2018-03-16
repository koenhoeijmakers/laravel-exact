<?php

namespace KoenHoeijmakers\LaravelExact\Services;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use JsonSerializable;
use KoenHoeijmakers\LaravelExact\Client;
use KoenHoeijmakers\LaravelExact\Exactoquent\Builder;

abstract class Service implements JsonSerializable, Arrayable
{
    /**
     * The resource uri.
     *
     * @var string
     */
    protected $resourceUri;

    /**
     * The exact client.
     *
     * @var \KoenHoeijmakers\LaravelExact\Client
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
     * The primary key of the service.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * Service constructor.
     *
     * @param array                                $attributes
     * @param \KoenHoeijmakers\LaravelExact\Client $client
     */
    public function __construct(Client $client, array $attributes = [])
    {
        $this->fill($attributes);

        $this->client = $client;
    }

    /**
     * Fill the model with the given attributes.
     *
     * @param array $attributes
     * @return $this
     */
    public function fill(array $attributes = [])
    {
        $this->fillableFromArray($attributes);

        return $this;
    }

    /**
     * Save the model to exact.
     *
     * @return \KoenHoeijmakers\LaravelExact\Services\Service
     */
    public function save()
    {
        if ($this->exists()) {
            return $this->fill(
                $this->getClient()->put($this->getPrimaryKeyAppendedResourceUri(), $this->getCastedAttributes())
            );
        }

        return $this->fill(
            $this->getClient()->post($this->getResourceUri(), $this->getCastedAttributes())
        );
    }

    /**
     * Update the current model.
     *
     * @param array $attributes
     * @return \KoenHoeijmakers\LaravelExact\Services\Service
     */
    public function update($attributes = [])
    {
        return $this->fill($attributes)->save();
    }

    /**
     * Delete the model.
     *
     * @return \KoenHoeijmakers\LaravelExact\Services\Service
     */
    public function delete()
    {
        $this->getClient()->delete($this->getPrimaryKeyAppendedResourceUri());

        return $this;
    }

    /**
     * Get the resource uri with the guid (used for updating and deleting).
     *
     * @return string
     */
    protected function getPrimaryKeyAppendedResourceUri()
    {
        return $this->getResourceUri() . '(guid\'' . $this->getKey() . '\')';
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
     * Whether the model exists.
     *
     * @return bool
     */
    public function exists()
    {
        return !is_null($this->getKey());
    }

    /**
     * Get the cast type for the given attribute.
     *
     * @param $attribute
     * @return mixed
     */
    protected function getCastType($attribute)
    {
        return Arr::get($this->casts, $attribute);
    }

    /**
     * Get all casts.
     *
     * @return array
     */
    protected function getCasts()
    {
        return $this->casts;
    }

    /**
     * Cast the given value to the given type.
     *
     * @param $value
     * @param $type
     * @return bool|int
     */
    protected function cast($value, $type)
    {
        switch ($type) {
            case 'bool':
            case 'boolean':
                return (bool) $value;
                break;

            case 'int':
            case 'integer':
                return (int) $value;
                break;

            case 'float':
            case 'double':
            case 'real':
                return (float) $value;
                break;

            case 'string':
                return (string) $value;
                break;
        }

        return $value;
    }

    /**
     * Whether the given attribute has a cast.
     *
     * @param $attribute
     * @return bool
     */
    protected function hasCast($attribute)
    {
        return array_key_exists($attribute, $this->getAttributes());
    }

    /**
     * Get the attributes, but casted if necessary.
     *
     * @return array
     */
    protected function getCastedAttributes()
    {
        $attributes = $this->getAttributes();

        if (!$this->hasCasts()) {
            return $attributes;
        }

        foreach ($attributes as $attribute => $value) {
            if ($this->hasCast($attributes)) {
                $attributes[$attribute] = $this->cast($value, $this->getCastType($attribute));
            }
        }

        return $attributes;
    }

    /**
     * Whether the service has casts.
     *
     * @return bool
     */
    protected function hasCasts()
    {
        return !empty($this->getCasts());
    }

    /**
     * Get a new instance.
     *
     * @param array $attributes
     * @return static
     */
    public function getNewInstance(array $attributes = [])
    {
        return new static($this->getClient(), $attributes);
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
        return Arr::get($this->attributes, $attribute);
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
     * @return \KoenHoeijmakers\LaravelExact\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Get the primary key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->getAttribute($this->getKeyName());
    }

    /**
     * Get the primary key name.
     *
     * @return string
     */
    public function getKeyName()
    {
        return $this->primaryKey;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->getCastedAttributes();
    }

    /**
     * Specify data which should be serialized to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getCastedAttributes();
    }

    /**
     * Send a search query to exact.
     *
     * @param array    $wheres
     * @param int|null $top
     * @return array
     */
    public function search(array $wheres = [], $top = null)
    {
        $builder = $this->newBuilder()->top($top);

        foreach ($wheres as $where) {
            $builder->where(...$where);
        }

        return $builder->get();
    }

    /**
     * Find one.
     *
     * @param $value
     * @return mixed
     */
    public function find($value)
    {
        return $this->newBuilder()->find($value);
    }

    /**
     * Get a new builder instance.
     *
     * @return \KoenHoeijmakers\LaravelExact\Exactoquent\Builder
     */
    public function newBuilder()
    {
        return new Builder(new static($this->getClient()));
    }

    /**
     * Parse the results into an array of objects.
     *
     * @param array $data
     * @return array
     */
    protected function parseResultsIntoServiceObjects(array $data = [])
    {
        return array_map(function ($attributes) {
            return $this->getNewInstance($attributes);
        }, $data);
    }
}
