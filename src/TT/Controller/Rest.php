<?php

namespace TT\Controller;

use TT\Locator;

/**
 * Description of Rest
 *
 * @author tt
 */
abstract class Rest {

    protected $request;
    protected $response;
    protected $responseCode;

    public function __construct(Locator $sl) {
        $this->sl = $sl;
        $this->request = $sl->request;
        $this->dbm = $sl->dbm;
    }

    final public function getResponseCode() {
        return $this->responseCode;
    }

    final public function getResponse() {
        return $this->response;
    }

    // @codeCoverageIgnoreStart
    abstract public function get();

    abstract public function post();

    abstract public function put();

    abstract public function delete();
    // @codeCoverageIgnoreEnd
}
