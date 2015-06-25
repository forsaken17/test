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
        'api' => ['module' => 'Api', 'anonymity' => true],
        'test' => ['module' => 'Api'],
        'logout' => ['module' => 'User'],
        'inbox' => ['module' => 'Task'],
        'archive' => ['module' => 'Task'],
        'delete' => ['module' => 'Task'],
        'edit' => ['module' => 'Task'],
        'changeState' => ['module' => 'Task'],
        'changeCategory' => ['module' => 'Task'],
    ];
    public static $action;
    public static $apiAction;

    public static function getActionParams() {
        return self::$list[self::$action];
    }

    public static function getAction(Request $request) {
        $uriArray = explode('?', $request->server['REQUEST_URI']);
        self::parsePath($uriArray[0]);
        if (!array_key_exists(self::$action, \TT\Router::$list)) {
            self::$action = 'api';
        }
        return self::$action;
    }

    private static function parsePath($pathRaw) {
        list($action, $apiAction) = array_merge(explode('/', trim($pathRaw, '/')), ['']);
        $action = filter_var($action, FILTER_SANITIZE_STRING);
        $apiAction = filter_var($apiAction, FILTER_SANITIZE_STRING);
        if (!empty($action) && array_key_exists($action, \TT\Router::$list)) {
            self::$action = $action;
        }
        if ('api' === self::$action && !empty($apiAction) && array_key_exists($apiAction, \TT\Router::$list)) {
            self::$apiAction = $apiAction;
        }
    }

}
