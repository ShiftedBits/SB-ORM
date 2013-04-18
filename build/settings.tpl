<?php

$GLOBALS['settings'] = array(
    'paths' => array(
        ROOT . D . 'source',
        ROOT . D . 'source' . D . 'clauses',
        ROOT . D . 'source' . D . 'filters',
        ROOT . D . 'source' . D . 'sources',
        ROOT . D . 'source' . D . 'queries',
        ROOT . D . 'source' . D . 'support',
    ),
    'database' => array(
        'host'    => '${database.host}',
        'dbname'  => '${database.name}',
        'user'    => '${database.user}',
        'pass'    => '${database.pass}',
    ),
);