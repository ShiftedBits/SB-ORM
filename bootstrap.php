<?php

define("ROOT", dirname(__FILE__));
define("D", DIRECTORY_SEPARATOR);

include ROOT . D . 'settings.php';

spl_autoload_register(
    function($class) {
        require(ROOT . D . "$class.php");
    }
);