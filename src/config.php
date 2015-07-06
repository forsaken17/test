<?php

define('PROJ_ROOT', realpath(__DIR__));
define('SALT', 'my_key');

function config() {
    return [
        'dsn' => 'mysql:host=127.0.0.1;dbname=ptest;charset=UTF8',
        'dbuser' => 'root',
        'dbpassword' => '',
    ];
}

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
