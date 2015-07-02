<?php

namespace TT\Controller;

/**
 *
 * @author tt
 */
class BxUser extends Rest {

    public function get() {
        $this->response = array('TestResponse' => 'I am GET response. Variables sent are - ' . http_build_query($this->request->getParams()));
        $this->responseCode = 200;
    }

    public function post() {
        $this->response = array('TestResponse' => 'I am POST response. Variables sent are - ' . http_build_query($this->request->getParams()));
        $this->responseCode = 201;
    }

    public function put() {
        $this->response = array('TestResponse' => 'I am PUT response. Variables sent are - ' . http_build_query($this->request->getParams()));
        $this->responseCode = 200;
    }

    public function delete() {
        $this->response = array('TestResponse' => 'I am DELETE response. Variables sent are - ' . http_build_query($this->request->getParams()));
        $this->responseCode = 200;
    }

}
