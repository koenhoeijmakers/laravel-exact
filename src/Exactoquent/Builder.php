<?php

declare(strict_types=1);

namespace KoenHoeijmakers\LaravelExact\Exactoquent;

use Illuminate\Support\Arr;
use KoenHoeijmakers\LaravelExact\Services\Service;

class Builder
{
    /**
     * The service.
     *
     * @var \KoenHoeijmakers\LaravelExact\Services\Service
     */
    protected $service;

    /**
     * The wheres.
     *
     * @var array
     */
    protected $wheres = [];

    /**
     * The expansions.
     *
     * @var array
     */
    protected $expansions = [];

    /**
     * The top.
     *
     * @var int|null
     */
    protected $top = null;

    /**
     * The select.
     *
     * @var array
     */
    protected $select = [];

    /**
     * Builder constructor.
     *
     * @param  \KoenHoeijmakers\LaravelExact\Services\Service $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * Get all the results.
     *
     * @return array
     */
    public function all()
    {
        return $this->newBuilder()->get();
    }

    /**
     * Find a single model by its primary key.
     *
     * @param              $value
     * @param  string|null $key
     * @return array
     */
    public function find($value, $key = null)
    {
        return $this->newBuilder()->where($key ?? $this->getService()->getKeyName(), 'eq', $value)->first();
    }

    /**
     * Add a new where.
     *
     * @param  string $field
     * @param  string $operator
     * @param  mixed  $value
     * @return $this
     */
    public function where(string $field, string $operator, $value)
    {
        $this->wheres[] = [$field, $operator, $value];

        return $this;
    }

    /**
     * The select.
     *
     * @param  array $values
     * @return $this
     */
    public function select(array $values = [])
    {
        $this->select = $values;

        return $this;
    }

    /**
     * Top it off.
     *
     * @param  int $top
     * @return $this
     */
    public function top(int $top)
    {
        $this->top = $top;

        return $this;
    }

    /**
     * @param  array $attributes
     * @return \KoenHoeijmakers\LaravelExact\Services\Service
     */
    protected function newService(array $attributes = [])
    {
        return $this->getService()->getNewInstance($attributes);
    }

    /**
     * Get all results.
     *
     * @return array
     */
    public function get()
    {
        return $this->fire();
    }

    /**
     * Get the first result.
     *
     * @return array
     */
    public function first()
    {
        return $this->top(1)->fire();
    }

    /**
     * Fire the query.
     *
     * @return array
     */
    protected function fire()
    {
        $service = $this->getService();

        $result = $service->getClient()->get(
            $service->getResourceUri(), $this->prepareQuery()
        );

        if ($this->top === 1) {
            return Arr::first($result, null, []);
        }

        return $result;
    }

    /**
     * Prepare the query so it can be sent.
     *
     * @return array
     */
    protected function prepareQuery()
    {
        $params = [];

        if (! is_null($top = $this->top)) {
            $params['$top'] = $top;
        }

        if (! empty($wheres = $this->wheres)) {
            $params['$filter'] = implode(',', array_map(function ($where) {
                return implode(' ', $where);
            }, $wheres));
        }

        if (! is_null($expansions = $this->expansions)) {
            $params['$expansion'] = implode(',', $expansions);
        }

        $params['$select'] = $this->select ?? $this->getService()->getFillable();

        return $params;
    }

    /**
     * Get the service.
     *
     * @return \KoenHoeijmakers\LaravelExact\Services\Service
     */
    protected function getService(): Service
    {
        return $this->service;
    }

    /**
     * Get a new builder.
     *
     * @return \KoenHoeijmakers\LaravelExact\Exactoquent\Builder
     */
    protected function newBuilder(): Builder
    {
        return new static($this->getService());
    }
}
