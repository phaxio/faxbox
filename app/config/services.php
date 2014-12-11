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
        'public' => '', // This will be fetched from the settings table
        'secret' => '', // This will be fetched from the settings table
    ],
    
    'mailgun'  => [
        'domain' => '', // This will be fetched from the settings table
        'secret' => '', // This will be fetched from the settings table
    ],
];
