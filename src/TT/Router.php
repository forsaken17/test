<?php

namespace TT;

/**
 * basic routing
 * @author tt
 */
class Router {

    public static $list = [
        'login' => ['module' => 'User', 'anonymity' => true],
        'register' => ['module' => 'User', 'anonymity' => true],
        'logout' => ['module' => 'User'],
        'inbox' => ['module' => 'Task'],
        'archive' => ['module' => 'Task'],
        'delete' => ['module' => 'Task'],
        'edit' => ['module' => 'Task'],
        'changeState' => ['module' => 'Task'],
        'changeCategory' => ['module' => 'Task'],
    ];
    public static $action;

    public static function getAction() {
        $uriArray = explode('?', $_SERVER['REQUEST_URI']);
        if (!empty($uriArray[0]) && substr($uriArray[0], 0, 1) === '/') {
            self::$action = trim($uriArray[0], '/');
        }
        if (!array_key_exists(self::$action, \TT\Router::$list)) {
            self::$action = 'inbox';
        }
        return self::$action;
    }

    public static function execute($action) {
        $className = 'TT\\Controller\\' . self::$list[$action]['module'];
        $control = new $className(Locator::instance());
        return $control->$action();
    }

}
