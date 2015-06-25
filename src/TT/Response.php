<?php

namespace TT;

/**
 * Description of Response
 *
 * @author tt
 */
class Response {

    private $data = [];
    private $error = [];
    private $type;

    public function __construct($type = 'json') {
        $this->type = $type;
    }

    public function getData() {
        return $this->data;
    }

    public function setData(array $data) {
        $this->data = $data;
    }

    public function addError($msg) {
        return $this->error[] = $msg;
    }

    private function getJsonError() {
        return $this->error['json'] = json_last_error_msg();
    }

    public function getJson($body) {
        return json_encode($body);
    }

    public function __toString() {
        $string = '';

        $body = ['data' => $this->data, 'error' => $this->error];
        if ('json' === $this->type) {
            if (false === ($string = $this->getJson($body))) {
                $string = $this->getJson($this->getJsonError());
            }
        } else {
            $string = serialize($body);
        }
        return $string;
    }

}
