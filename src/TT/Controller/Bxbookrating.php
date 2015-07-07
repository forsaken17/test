<?php

namespace TT\Controller;

/**
 *
 * @author tt
 */
class Bxbookrating extends Rest {

    public function get() {
        $this->response = ['GET'];
        $this->responseCode = 200;
    }

    public function post() {
        $this->response = ['POST'];
        $this->responseCode = 201;
    }

    public function put() {
        $this->response = ['PUT'];
        $this->responseCode = 200;
    }

    public function delete() {
        $this->response = ['DELETE'];
        $this->responseCode = 200;
    }

}
