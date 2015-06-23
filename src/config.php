<?php

define('PROJ_ROOT', realpath(__DIR__));
define('SALT', 'my_key');
$dsn = 'mysql:host=127.0.0.1;dbname=tododb;charset=UTF8';
$user = 'root';
$password = 'root';

function autoload($className) {
    $className = ltrim($className, '\\');
    $fileName = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require $fileName;
}

spl_autoload_register('autoload');

require_once PROJ_ROOT . '/Helpers/view.php';
