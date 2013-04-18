<?php

$GLOBALS['settings'] = array(
    'paths' => array(
        ROOT . D . 'source',
        ROOT . D . 'source' . D . 'clauses',
        ROOT . D . 'source' . D . 'filters',
        ROOT . D . 'source' . D . 'sources',
    ),
    'database' => array(
        'host'    => '${database.host}',
        'dbname'  => '${database.name}',
        'user'    => '${database.user}',
        'pass'    => '${database.pass}',
    ),
);