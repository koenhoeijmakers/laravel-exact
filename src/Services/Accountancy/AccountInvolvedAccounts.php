<?php

namespace KoenHoeijmakers\LaravelExact\Services\Accountancy;

use KoenHoeijmakers\LaravelExact\Services\Service;

class AccountInvolvedAccounts extends Service
{
    /**
     * The resource uri.
     *
     * @var string
     */
    protected $resourceUri = '/api/v1/{division}/accountancy/AccountInvolvedAccounts';

    /**
     * The model's fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'ID',
        'Account',
        'AccountName',
        'Created',
        'Creator',
        'CreatorFullName',
        'Division',
        'InvolvedAccount',
        'InvolvedAccountRelationTypeDescription',
        'InvolvedAccountRelationTypeDescriptionTermId',
        'InvolvedAccountRelationTypeId',
        'Modified',
        'Modifier',
        'ModifierFullName',
        'Notes',
    ];
}
