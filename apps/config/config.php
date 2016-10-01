<?php

return array(
    'web_name'      => "Marketing HQ Technology Solutions",
    'front_name'    => " - Marketing HQ Technology Solutions",
    'back_name'     => " - Backend - Marketing HQ Technology Solutions",
    'acl'           => false,
    'profiler'      => false,
    'baseUrl'       => '/',
    'staticBaseUrl' => '/',
    'router'        => array(
        "default_module" => "mkt"
    ),
    'modules'       => array(
        "hqend", "mkt"
    ),
    'cache'         =>
    array(
        'lifetime' => 86400,
        'prefix'   => 'hq_',
        'adapter'  => 'File',
        'cacheDir' => ROOT_PATH . '/apps/data/cache/data/',
    ),
    'logger'        =>
    array(
        'enabled' => true,
        'path'    => ROOT_PATH . '/apps/data/logs/',
        'format'  => '[%date%][%type%] %message%',
    ),
    'view'          =>
    array(
        'compiledPath'      => ROOT_PATH . '/apps/data/cache/views/',
        'compiledExtension' => '.php',
        'compiledSeparator' => '_',
        'compileAlways'     => true,
    ),
    'session'       =>
    array(
        'adapter'  => 'Files',
        'uniqueId' => 'Hq_',
    ),
    'assets'        =>
    array(
        'local'    => '/public/',
        'cdn'      => 'http://mkt.hqsolu.com/',
        'remote'   => false,
        'lifetime' => 0,
        'join'     => false
    ),
    'metadata'      =>
    array(
        'adapter'     => 'Files',
        'metaDataDir' => ROOT_PATH . '/apps/data/cache/metadata/',
    ),
    'annotations'   =>
    array(
        'adapter'        => 'Files',
        'annotationsDir' => ROOT_PATH . '/apps/data/cache/annotations/',
    ),
    'languages'     =>
    array(
        'cacheDir' => ROOT_PATH . '/apps/data/cache/languages/',
        'list'     => array('en' => 'en_us', "vn" => "vi_vn"),
        'locale'   => 'vi_vn',
        'language' => 'vi'
    ),
);
