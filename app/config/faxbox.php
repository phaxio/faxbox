<?php

return [

    /*
	|--------------------------------------------------------------------------
	| Permissions
	|--------------------------------------------------------------------------
	|
    | WARNING: Don't change these unless you know what you're doing. It could 
    | break everything!
    | 
	| This sets up the available permissions for the system. Any permission that
    | contains the string "phone_" in the name will be applied to each phone 
    | number in the "phones" table. 
	|
	*/
    
    'permissions' => [
        [
            // Sending Faxes
            'name' => 'admin',
            'short' => 'Administrator',
            'description' => 'Has unrestricted access to everything',
            'restrictedRoutes' => [
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
            'name' => 'phone_manage_%d',
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