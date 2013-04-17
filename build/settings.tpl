<?php

$GLOBALS['settings'] = array(
    'paths' => array(
        ROOT . D . 'framework' . D . 'orm',
    ),
    'database' => array(
        'host'    => '${database.host}',
        'dbname'  => '${database.name}',
        'user'    => '${database.user}',
        'pass'    => '${database.pass}',
    ),
);