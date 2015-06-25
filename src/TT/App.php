<?php

namespace TT;

/**
 * wrapper
 *
 * @author tt
 */
class App {

    public static function run() {
        try {
            $sl = Locator::instance();
            $action = Router::getAction(Request::instance());
            $cfg = config();
            $sl->db = new \PDO($cfg['dsn'], $cfg['dbuser'], $cfg['dbpassword']);
            $sl->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $sl->dbm = new Model\Manager();
            $sl->auth = new Auth();
            $sl->view = new View();
            if ($sl->auth->verify()) {
                \redirect(\url('login'));
            }
            echo $response = self::execute($action);
        } catch (\PDOException $e) {
            echo 'PDO failed: ' . $e->getMessage();
        } catch (\Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public static function execute($action) {
        $className = 'TT\\Controller\\' . Router::getActionParams()['module'];
        $control = new $className(Locator::instance());
        return $control->$action();
    }

}
