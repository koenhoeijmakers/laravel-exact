<?php

namespace KoenHoeijmakers\LaravelExact\Services\Accountancy;

use KoenHoeijmakers\LaravelExact\Services\Service;

class InvolvedUsers extends Service
{
    /**
     * The resource uri.
     *
     * @var string
     */
    protected $resourceUri = '/api/v1/{division}/accountancy/InvolvedUsers';

    /**
     * The model's fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'ID',
        'Account',
        'AccountCity',
        'AccountCode',
        'AccountIsSupplier',
        'AccountLogoThumbnailUrl',
        'AccountName',
        'AccountStatus',
        'Created',
        'Creator',
        'CreatorFullName',
        'Division',
        'InvolvedUserRole',
        'InvolvedUserRoleDescription',
        'IsMainContact',
        'Modified',
        'Modifier',
        'ModifierFullName',
        'PersonEmail',
        'PersonPhone',
        'PersonPhoneExtension',
        'PersonPictureThumbnailUrl',
        'User',
        'UserFullName',
    ];
}
