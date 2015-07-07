<?php

namespace TT;

/**
 * basic routing
 * @author tt
 */
class Router {

    public static $list = [
        'bxbookrating/ranking' => ['module' => 'Api', 'resource' => ['bxbookrating', 'ranking'], 'anonymity' => true],
        'auth' => ['module' => 'Api', 'anonymity' => true],
        'api' => ['module' => 'Api'],
        'test' => ['module' => 'Api'],
        'bxbookrating' => ['module' => 'Api', 'resource' => 'bxbookrating'],
        'bxbook' => ['module' => 'Api', 'resource' => 'bxbook'],
        'bxbook/{isbn}' => ['module' => 'Api', 'resource' => 'bxbook'],
        'bxuser' => ['module' => 'Api', 'resource' => 'bxuser'],
        'bxuser/{id}' => ['module' => 'Api', 'resource' => 'bxuser'],
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
        if (!array_key_exists(self::$action, self::$list)) {
            self::$action = 'api';
        }
        return self::$action;
    }

    private static function parsePath($pathRaw) {
        $pathRaw = preg_replace('@[\&\?](.*)@', '', $pathRaw);
        preg_match_all('@([^\/]*)@', $pathRaw, $actionList);
        if (empty($actionList[1])) {
            return false;
        }
        $actionList = array_filter($actionList[1], function(&$val) {
            return $val = filter_var($val, FILTER_SANITIZE_STRING);
        });
        $action = array_shift($actionList);
        if (!empty($action) && array_key_exists($action, self::$list)) {
            self::$action = $action;
        }
        self::findApiAction($actionList);
    }

    private static function findApiAction($actionList) {
        $apiAction = array_filter(array_keys(self::$list), function(&$val) use ($actionList) {
            $rAction = explode('/', trim($val, '/'));
            $result = 0;
            for ($i = 0; $i < count($actionList); $i++) {
                if (!empty($rAction[$i]) && $rAction[$i] === $actionList[$i]) {
                    $result++;
                } else if (!empty($rAction[$i]) && preg_match('@\{([^\}]*)}@', $rAction[$i], $match) && !empty($actionList[$i])) {
                    Request::instance()->set($match[1], $actionList[$i]);
                    $result++;
                } else {
                    $result--;
                }
            }
            return $result > 0;
        });

        if ('api' === self::$action && !empty($apiAction = array_shift($apiAction))) {
            self::$apiAction = $apiAction;
        }
    }

}
