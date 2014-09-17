<?php

return array(
    'default' => safe_getenv('database.default'),

    'connections' => array(
        'mysql' => array(
            'driver'   => 'mysql',
            'database' => safe_getenv('database.database'),
            'host' => safe_getenv('database.host'),
            'username'  => safe_getenv('database.username'),
            'password'  => safe_getenv('database.password'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ),

        'sqlite' => array(
            'driver'   => 'sqlite',
            'database' => safe_getenv('database.database'),
            'prefix'   => '',
        ),
    )

);
