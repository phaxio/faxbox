<?php

return [

    'version' => 0.1,
    
    'installed' => false,

    'notify' => [
        'fax' => action('NotifyController@fax'),
        'send' => action('NotifyController@sendFromEmail', ['number' => null])
    ],
    
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
    'permissions'    => [

        'static'  => [
            [
                // Sending Faxes
                'id'          => 'superuser',
                'name'        => 'Administrator',
                'description' => 'Has unrestricted access to everything'
            ],
            [
                // Sending Faxes
                'id'          => 'send_fax',
                'name'        => 'Send Faxes',
                'description' => 'Can send faxes.'
            ],
            [
                // Updating application settings
                'id'          => 'update_settings',
                'name'        => 'Update Settings',
                'description' => 'Can update application settings (ie. API Key, SMTP, etc).'
            ],
            [
                // Purchase Numbers
                'id'          => 'purchase_numbers',
                'name'        => 'Purchase Numbers',
                'description' => 'Can purchase phone numbers from Phaxio.'
            ]
        ],
        'dynamic' => [
            [
                'className'   => 'Faxbox\Repositories\Phone\PhoneInterface',
                'niceName'    => 'Phone Number',
                'permissions' => [
                    [
                        'id'          => 'view',
                        'name'        => 'View faxes from {number}',
                        'description' => 'Can view a fax for the number {number}.',
                    ]
                ],
            ]
        ]
    ],
    'supportedFiles' => [
        [
            'ext'  => 'pdf',
            'mime' => 'application/pdf'
        ],
        [
            'ext'  => 'doc',
            'mime' => 'application/msword'
        ],
        [
            'ext'  => 'docx',
            'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ],
        [
            'ext'  => 'odt',
            'mime' => 'application/vnd.oasis.opendocument.text'
        ],
        [
            'ext'  => 'txt',
            'mime' => 'text/plain'
        ],
        [
            'ext'  => 'html',
            'mime' => 'text/html'
        ],
        [
            'ext'  => 'rtf',
            'mime' => 'text/rtf'
        ],
        [
            'ext'  => 'jpeg',
            'mime' => 'image/jpeg'
        ],
        [
            'ext'  => 'jpg',
            'mime' => 'image/jpg'
        ],
        [
            'ext'  => 'tiff',
            'mime' => 'image/tiff'
        ],
        [
            'ext'  => 'png',
            'mime' => 'image/png'
        ]
    ],
    /**
     * These are the supported countries that we can fax to
     */
    'phone' => [
        [
            'name'   => 'United States',
            'code'   => 1,
            'short'  => 'us',
        ],
        [
            'name'   => 'Canada',
            'code'   => 1,
            'short'  => 'ca',
        ],
        [
            'name'  => 'United Kingdom',
            'code'  => 44,
            'short' => 'gb'
        ],
        [
            'name'  => 'Japan',
            'code'  => 81,
            'short' => 'jp'
        ],
        [
            'name'  => 'France',
            'code'  => 33,
            'short' => 'fr'
        ],
        [
            'name'  => 'Germany',
            'code'  => 49,
            'short' => 'de'
        ],
        [
            'name'  => 'Argentina',
            'code'  => 54,
            'short' => 'ar'
        ],
        [
            'name'  => 'Brazil',
            'code'  => 55,
            'short' => 'bz'
        ],
        [
            'name'  => 'Israel',
            'code'  => 972,
            'short' => 'il'
        ],
        [
            'name'  => 'India',
            'code'  => 91,
            'short' => 'in'
        ],
        [
            'name'  => 'Portugal',
            'code'  => 351,
            'short' => 'pt'
        ],
        [
            'name'  => 'Italy',
            'code'  => 39,
            'short' => 'it'
        ],
        [
            'name'  => 'Hong Kong',
            'code'  => 852,
            'short' => 'hk'
        ],
        [
            'name'   => 'Puerto Rico',
            'code'   => 1,
            'short'  => 'pr',
        ],
        [
            'name'  => 'Australia',
            'code'  => 61,
            'short' => 'au'
        ]

    ],

];