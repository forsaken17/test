<?php

function showMessage($type) {
    $msg = \TT\Locator::instance()->auth->getSessionVar("{$type}_msg");
    \TT\Locator::instance()->auth->setSessionVar("{$type}_msg", null);
    return $msg;
}

/**
 *
 * @param type $str
 * @return type
 */
function url($str = '') {
    $str = ltrim($str, '/');
    if (substr($str, 0, 1) === '?') {
        $str = 'index.php' . $str;
    }
    return 'http://' . $_SERVER['HTTP_HOST'] . '/' . $str;
}

/**
 *
 * @param type $url
 * @param type $statusCode
 */
function redirect($url, $statusCode = 303) {
    header('Location: ' . $url, true, $statusCode);
    exit;
}

/**
 *
 * @param type $category
 * @return type
 */
function isArchive($category) {
    return $category == \TT\Model\Task::CATEGORY_ARCHIVE;
}

/**
 *
 * @param type $category
 * @return type
 */
function isDone($category) {
    return $category == \TT\Model\Task::STATE_DONE;
}

/**
 *
 * @return type
 */
function isLogged() {
    return \TT\Locator::instance()->auth->isSignedIn();
}

function action() {
    return \TT\Router::$action ? \TT\Router::$action : 'inbox';
}
