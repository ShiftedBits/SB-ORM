<?php

define("ROOT", dirname(__FILE__));
define("D", DIRECTORY_SEPARATOR);
define('PS', D == "/" ? ":" : ";");

include ROOT.D.'settings.php';
$settings = $GLOBALS['settings'];
$paths    = implode(PS, $settings['paths']);
ini_set('include_path', $paths . PS . ini_get('include_path'));

spl_autoload_register(
    function($class) {
        require("$class.php");
    }
);