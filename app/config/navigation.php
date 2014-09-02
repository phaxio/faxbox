<?php 
return [
    [
        'label' => 'Faxes',
        'route' => 'faxes',
        'active' => [ 'faxes', 'faxes/create', 'faxes/*' ]
    ],
    [
        'label' => 'Phone Numbers',
        'route' => 'phones',
        'access' => 'purchase_numbers',
        'active' => [ 'phones', 'phones/create', 'phones/*/edit' ]
    ],
    [
        'label' => 'Groups',
        'route' => 'groups',
        'access' => 'superuser',
        'active' => [ 'groups', 'groups/create', 'groups/*/edit' ]
    ],
    [
        'label' => 'Users',
        'route' => 'users',
        'access' => 'superuser',
        'active' => [ 'users', 'users/create', 'users/*/edit' ]
    ],
    [
        'label' => 'Settings',
        'route' => '#',
        'access' => 'update_settings',
        'active' => [ 'settings/api', 'settings/appearance', 'settings/mail' ],
        'sub' => [
            [
                'label' => 'Phaxio API Keys',
                'route' => 'settings/api',
                'active' => [ 'settings/api' ]
            ],
            [
                'label' => 'Appearance',
                'route' => 'settings/appearance',
                'active' => [ 'settings/appearance' ]
            ],
            [
                'label' => 'Mail Server',
                'route' => 'settings/mail',
                'active' => [ 'settings/mail' ]
            ],
        ]
    ]
];