<?php

return new \Phalcon\Config(array(
    'name'       => 'Core',
    'main_route' => "core",
    'database'   => array(
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'db_id_suregame',
        'password' => 'CHAuN5tuPQL8tx4B',
        'dbname'   => 'web_backend_core',
        'charset'  => 'utf8',
        'port'     => '3306'
    )
        ));
