<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'phaxio'   => [
        'public' => safe_getenv('services.phaxio.public'),
        'secret' => safe_getenv('services.phaxio.secret'),
    ],
    
    'mailgun'  => [
        'domain' => safe_getenv('services.mailgun.domain'),
        'secret' => safe_getenv('services.mailgun.secret'),
    ],
];
