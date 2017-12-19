<?php if (!defined("INITIALIZED")) die();

return array(
    'db' => array(
        'host' => 'localhost',
        'user' => 'libmemo',
        'password' => 'wZtOAGzNf0x84XcH',
        'database' => 'libmemo'
    ),
    'app' => array(
        'controllers' => $_SERVER['DOCUMENT_ROOT'].'/controllers/',
        'models' => $_SERVER['DOCUMENT_ROOT'].'/models/',
        'views' => $_SERVER['DOCUMENT_ROOT'].'/views/'
    ),
    'fs' => array(
        'upload' => '/upload/',
        'tmp' => '/tmp/',
        'cache' => '/cache/'
    )
);