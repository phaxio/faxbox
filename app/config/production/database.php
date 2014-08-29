<?
$config = array();

$config['database'] = array(
    'default' => $_ENV['database.type'],
    'connections' => array(
        $_ENV['database.type'] => array(
            'database' => $_ENV['database.database']
        )
    )
);


return $config;