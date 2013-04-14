<?php

$settings = array(
    'paths' => array(
        ROOT . D . 'framework' . D . 'context',
        ROOT . D . 'framework' . D . 'crypt',
        ROOT . D . 'framework' . D . 'database',
        ROOT . D . 'framework' . D . 'event',
        ROOT . D . 'framework' . D . 'filesystem',
        ROOT . D . 'framework' . D . 'input',
        ROOT . D . 'framework' . D . 'logging',
        ROOT . D . 'framework' . D . 'mailer',
        ROOT . D . 'framework' . D . 'model',
        ROOT . D . 'framework' . D . 'module',
        ROOT . D . 'framework' . D . 'output',
        ROOT . D . 'framework' . D . 'timer',
        ROOT . D . 'framework' . D . 'user',
        ROOT . D . 'libs' . D . 'smarty',
        ROOT . D . 'libs' . D . 'swift',
        ROOT . D . 'libs' . D . 'geshi',
        ROOT . D . 'models',
    ),
    'crypt' => array(
        'key'      => "${crypt.key}",
        'iv'       => "${crypt.iv}",
        'salt'     => '${crypt.salt}',
    ),
    'template' => array(
        'template' => ROOT . D . 'templates',
        'compiled' => ROOT . D . 'tpl_data' . D . 'compiled',
        'configs'  => ROOT . D . 'tpl_data' . D . 'configs',
        'cache'    => ROOT . D . 'tpl_data' . D . 'cache'
    ),
    'database' => array(
        'host'    => '${database.host}',
        'dbname'  => '${database.name}',
        'user'    => '${database.user}',
        'pass'    => '${database.pass}',
    ),
    'logging' => array(
        'filepath' => ROOT . D . 'logs' . D . 'error_log'
    ),
);