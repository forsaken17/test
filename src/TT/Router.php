<?php

namespace TT;

/**
 * basic routing
 * @author tt
 */
class Router {

    public static $list = [
        'bxbookrating/{id}/ranking' => ['module' => 'Api', ],
        'bxbookrating/ranking' => ['module' => 'Api','resource'=>['bxbookrating', 'ranking'], 'anonymity' => true],
        'auth' => ['module' => 'Api', 'anonymity' => true],
        'api' => ['module' => 'Api'],
        'test' => ['module' => 'Api'],
        'bxbook' => ['module' => 'Api','resource'=>'bxbook'],
        'bxuser' => ['module' => 'Api','resource'=>'bxuser'],
        //
        'login' => ['module' => 'User', 'anonymity' => true],
        'register' => ['module' => 'User', 'anonymity' => true],
        'logout' => ['module' => 'User'],
        //
        'inbox' => ['module' => 'Task'],
        'archive' => ['module' => 'Task'],
        'delete' => ['module' => 'Task'],
        'edit' => ['module' => 'Task'],
        'changeState' => ['module' => 'Task'],
        'changeCategory' => ['module' => 'Task'],
        //
    ];
    public static $action;
    public static $apiAction;

    public static function getActionParams($actionName = null) {
        return isset(self::$list[$actionName]) ? self::$list[$actionName] : self::$list[self::$action];
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

        preg_match_all('@([^\/]*)@', $pathRaw, $actionList);
        if(empty($actionList[1])){
            return false;
        }
        $actionList = array_filter($actionList[1], function(&$val){
            return $val = filter_var($val, FILTER_SANITIZE_STRING);
        });


        $action = array_shift($actionList);

        if (!empty($action) && array_key_exists($action, \TT\Router::$list)) {
            self::$action = $action;
        }

        $apiAction = array_filter(array_keys(\TT\Router::$list), function(&$val) use ($actionList) {
            $rAction = explode('/', trim($val, '/'));
            $result = 0;
            for ($i = 0; $i < count($actionList); $i++) {
                if ($rAction[$i] === $actionList[$i] || (preg_match('@\{[^\}]*}@', $rAction[$i]) && is_numeric($actionList[$i]))) {
                    $result++;
                } else {
                    $result--;
                }
            }
            return $result > 0;
        });

        if ('api' === self::$action && !empty($apiAction[0])) {
            self::$apiAction = $apiAction[0];
        }
    }

}
