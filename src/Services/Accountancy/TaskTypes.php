<?php

namespace KoenHoeijmakers\LaravelExact\Services\Accountancy;

use KoenHoeijmakers\LaravelExact\Services\Service;

class TaskTypes extends Service
{
    /**
     * The resource uri.
     *
     * @var string
     */
    protected $resourceUri = '/api/v1/{division}/accountancy/TaskTypes';

    /**
     * The model's fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'ID',
        'Created',
        'Creator',
        'CreatorFullName',
        'Description',
        'DescriptionTermID',
        'Division',
        'Modified',
        'Modifier',
        'ModifierFullName',
    ];
}
