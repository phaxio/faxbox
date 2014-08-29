<?php

require_once('safeenv.php');

return array(
    'default' => 'main',

    'connections' => array(
        'main' => array(
            'driver'   => safe_getenv('database.type'),
            'database' => safe_getenv('database.database')
        )
    )

);
