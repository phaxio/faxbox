<?php

return [

    /*
	|--------------------------------------------------------------------------
	| Permissions
	|--------------------------------------------------------------------------
	|
	| todo: explain permissions
	|
	*/
    
    'permissions' => [
        [
            // Sending Faxes
            'name' => 'admin',
            'short' => 'Administrator',
            'description' => 'Has unrestricted access to everything',
            'restrictedRoutes' => [
                'fax.send'
            ]
        ],
        
        [
            // Sending Faxes
            'name' => 'send_fax',
            'short' => 'Send Faxes',
            'description' => 'Allows users to send faxes.',
            'restrictedRoutes' => [
                'fax.send'
            ]
        ],

        [
            // Manage a fax (view/delete)
            'name' => 'phone_view_%d',
            'short' => 'Manage faxes from +%d',
            'description' => 'Allows user to manage a fax for the number +%d',
            'restrictedRoutes' => [
                'fax.view',
                'fax.delete'
            ]
        ],

        [
            // Manage a phone number (currently only delete)
            'name' => 'phone_admin_%d',
            'short' => 'Manage +%d',
            'description' => 'Allows user to delete the phone number +%d',
            'restrictedRoutes' => [
                'phone.delete'
            ]
        ],

        [
            // Updating application settings
            'name' => 'update_settings',
            'short' => 'Update Settings',
            'description' => 'Allows users to update application settings (ie. API Key, SMTP, etc).',
            'restrictedRoutes' => [
                'settings.update'
            ]
        ],

        [  
            // Purchase Numbers
            'name' => 'purchase_numbers',
            'short' => 'Purchase Numbers',
            'description' => 'Allows users to purchase phone numbers from Phaxio',
            'restrictedRoutes' => [
                'number.purchase'
            ]
        ]
        
    ]
    
];