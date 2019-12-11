<?php

declare(strict_types=1);

namespace KoenHoeijmakers\LaravelExact\Services\Accountancy;

use KoenHoeijmakers\LaravelExact\Services\Service;

class AccountOwners extends Service
{
    /**
     * The resource uri.
     *
     * @var string
     */
    protected $resourceUri = '/api/v1/{division}/accountancy/AccountOwners';

    /**
     * The model's fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'ID',
        'Account',
        'AccountCode',
        'AccountName',
        'Created',
        'Creator',
        'CreatorFullName',
        'Division',
        'Modified',
        'Modifier',
        'ModifierFullName',
        'OwnerAccount',
        'OwnerAccountCode',
        'OwnerAccountName',
        'Shares',
    ];
}
