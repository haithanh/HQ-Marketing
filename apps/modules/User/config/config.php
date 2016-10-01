<?php

return new \Phalcon\Config(array(
    'name'       => 'User',
    'main_route' => "user",
    'database'   => array(
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'hqcompany_mkt',
        'password' => 'aZORgBwWhE',
        'dbname'   => 'hqcompany_mkt',
        'charset'  => 'utf8',
        'port'     => '3306'
    )
        ));
