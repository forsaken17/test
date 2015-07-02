<?php

namespace TT;

/**
 * Description of Response
 *
 * @author tt
 */
class Request {

    private $params = [];
    private $headers = [];

    private function __construct() {
        $this->server = $_SERVER;
        $this->headers = $this->getHeaderList();
        $this->method = strtolower($this->server['REQUEST_METHOD']);

        switch ($this->method) {
            case 'get':
            case 'post':
            case 'delete':
                $this->params = array_merge($_POST, $_GET);
                break;
            case 'put':
                parse_str(file_get_contents('php://input'), $this->params);
                break;
            default:
                break;
        }
    }

    private static $instance;

    public static function instance() {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function getHeaderList() {
        foreach ($this->server as $key => $value) {
            if (0 === strpos($key, 'HTTP_')) {
                $headers[substr($key, 5)] = $value;
            }
        }
        return $headers;
    }

    public function getHeader($name) {
        return isset($this->headers[$name]) ? $this->headers[$name] : null;
    }

    public function getParams() {
        return $this->params;
    }

    public function get($name, $filter = null) {
        $param = isset($this->params[$name]) ? $this->params[$name] : null;
        return filter_var($param, $filter);
    }

}
