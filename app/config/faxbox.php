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
            [
                // Sending Faxes
                'name' => 'sendFax',
                'description' => 'Allows users to send faxes.',
                'restrictedRoutes' => [
                    'fax.send'
                ]
            ],

            [
                // Manage a fax (view/delete)
                'name' => 'view_%d',
                'description' => 'Allows user to manage a fax for the number %d',
                'restrictedRoutes' => [
                    'fax.view',
                    'fax.delete'
                ]
            ],
    
            [
                // Manage a phone number (currently only delete)
                'name' => 'admin_%d',
                'description' => 'Allows user to delete the phone number %d',
                'restrictedRoutes' => [
                    'phone.delete'
                ]
            ],
    
            [
                // Updating application settings
                'name' => 'updateSettings',
                'description' => 'Allows users to update application settings (ie. API Key, SMTP, etc).',
                'restrictedRoutes' => [
                    'settings.update'
                ]
            ],
    
            [  
                // Purchase Numbers
                'name' => 'purchaseNumbers',
                'description' => 'Allows users to purchase phone numbers from Phaxio',
                'restrictedRoutes' => [
                    'number.purchase'
                ]
            ]
            
        ]
    ]
    
];