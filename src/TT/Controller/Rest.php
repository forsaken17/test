<?php

namespace TT\Controller;

/**
 * Description of Rest
 *
 * @author tt
 */
abstract class Rest {

    protected $request;
    protected $response;
    protected $responseCode;

    public function __construct($request) {
        $this->request = $request;
    }

    final public function getResponseCode() {
        return $this->responseCode;
    }

    final public function getResponse() {
        return $this->response;
    }

    public function checkUserPermission() {
        return true;
    }

    // @codeCoverageIgnoreStart
    abstract public function get();

    abstract public function post();

    abstract public function put();

    abstract public function delete();
    // @codeCoverageIgnoreEnd
}
