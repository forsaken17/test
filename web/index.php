<?php

require_once '../src/config.php';
/**
 * RUN
 */
try {
    $action = \TT\Router::getAction();
    $sl = \TT\Locator::instance();

    $sl->db = new \PDO($dsn, $user, $password);
    $sl->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    $sl->dbm = new \TT\Model\Manager($sl);
    $sl->auth = new \TT\Auth();
    $sl->view = new \TT\View();
    if (!$sl->auth->isSignedIn() && !$sl->auth->anonymAccess(\TT\Router::$list[$action])) {
        \redirect(\url('login'));
    }
    echo $response = \TT\Router::execute($action);
} catch (\PDOException $e) {
    echo 'PDO failed: ' . $e->getMessage();
} catch (\Exception $exc) {
    echo $exc->getMessage();
}

exit;
