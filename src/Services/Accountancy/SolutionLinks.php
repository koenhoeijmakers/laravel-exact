<?php

declare(strict_types=1);

namespace KoenHoeijmakers\LaravelExact\Services\Accountancy;

use KoenHoeijmakers\LaravelExact\Services\Service;

class SolutionLinks extends Service
{
    /**
     * The resource uri.
     *
     * @var string
     */
    protected $resourceUri = '/api/v1/{division}/accountancy/SolutionLinks';

    /**
     * The model's fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'ID',
        'Account',
        'Created',
        'Creator',
        'Division',
        'ExternalSolutionCode',
        'ExternalSolutionName',
        'ExternalSolutionUrl',
        'InternalSolutionDivision',
        'Modified',
        'Modifier',
        'Name',
        'OtherExternalSolutionName',
        'SolutionType',
        'Status',
    ];
}
