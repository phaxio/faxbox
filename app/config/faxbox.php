<?php

return [

    /*
	|--------------------------------------------------------------------------
	| Static Permissions
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
    
        'static' => [
            [
                // Sending Faxes
                'id' => 'superuser',
                'name' => 'Administrator',
                'description' => 'Has unrestricted access to everything'
            ],
            
            [
                // Sending Faxes
                'id' => 'send_fax',
                'name' => 'Send Faxes',
                'description' => 'Can send faxes.'
            ],
    
            [
                // Updating application settings
                'id' => 'update_settings',
                'name' => 'Update Settings',
                'description' => 'Can update application settings (ie. API Key, SMTP, etc).'
            ],
    
            [  
                // Purchase Numbers
                'id' => 'purchase_numbers',
                'name' => 'Purchase Numbers',
                'description' => 'Can purchase phone numbers from Phaxio.'
            ]
        ],
        
        'dynamic' => [
            [
                'className' => 'Faxbox\Repositories\Phone\PhoneInterface',
                'niceName' => 'Phone Number',
                'permissions' => [
                    [
                        'id' => 'view',
                        'name' => 'View faxes from {number}',
                        'description' => 'Can view a fax for the number {number}.',
                    ]
                ],
            ]
        ]
    ]
    
];