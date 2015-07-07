<?php

namespace TT\Controller;
use TT\Model\Bxuser as UserModel;
/**
 *
 * @author tt
 */
class Bxuser extends Rest {

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
        $user = new UserModel();
        $user->id = $this->request->get('id', FILTER_SANITIZE_NUMBER_INT);;
        if(!$this->dbm->delete($user)){
            throw new \Exception("Id: {$user->id} Not found", 400);
        }
        $this->response = [$user->id];
        $this->responseCode = 200;
    }

}
