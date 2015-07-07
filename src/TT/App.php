<?php

namespace TT;

/**
 * wrapper
 *
 * @author tt
 */
class App {

    private static $instance;

    public static function instance() {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function run() {
        try {
            $sl = Locator::instance();
            $sl->request = Request::instance();
            $action = Router::getAction($sl->request);
            $cfg = config();
            $opt  = [
                \PDO::MYSQL_ATTR_FOUND_ROWS   => true,
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ];
            $sl->db = new \PDO($cfg['dsn'], $cfg['dbuser'], $cfg['dbpassword'], $opt);

            $sl->dbm = new Model\Manager($sl->db);
            $sl->auth = new Auth();
            $sl->view = new View();
            if ($sl->auth->verify() && 'api' !== $action) {
                \redirect(\url('login'));
            }
            $response = self::execute($action);
            $this->send($response);
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

    private function send($response) {
        $code = Response::getCode();
        $contentType = Response::getContentType();
        $headers = 'HTTP/1.1 ' . $code . ' ' . Response::getStatusMessage($code);
        header($headers);
        header('Content-Type: ' . $contentType);
        echo (string) $response;
    }

}
