<?php

declare(strict_types=1);

namespace KoenHoeijmakers\LaravelExact\Services\Accountancy;

use KoenHoeijmakers\LaravelExact\Services\Service;

class InvolvedUserRoles extends Service
{
    /**
     * The resource uri.
     *
     * @var string
     */
    protected $resourceUri = '/api/v1/{division}/accountancy/InvolvedUserRoles';

    /**
     * The model's fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'ID',
        'Code',
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
