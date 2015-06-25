<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace TT\Controller;

/**
 * Description of Rest
 *
 * @author tt
 */
abstract class Rest {

    protected $request;
    protected $response;
    protected $responseStatus;

    public function __construct($request) {
        $this->request = $request;
    }

    final public function getResponseStatus() {
        return $this->responseStatus;
    }

    final public function getResponse() {
        return $this->response;
    }

    public function checkAuth() {
        return true;
    }

    // @codeCoverageIgnoreStart
    abstract public function get();

    abstract public function post();

    abstract public function put();

    abstract public function delete();
    // @codeCoverageIgnoreEnd
}
