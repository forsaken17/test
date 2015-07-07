<?php

namespace TT\Controller;

use TT\Request,
    TT\Response,
    TT\Router,
    TT\Locator;

/**
 *
 * @author tt
 */
class Api extends Front {

    public function __construct(Locator $sl) {
        parent::__construct($sl);
    }

    public function auth() {
        $response = $this->sl->create('response', 'json');
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = sha1(filter_input(INPUT_POST, 'password') . SALT);
        try {
            if (!($user = $this->dbm->findUserByEmailAndPassword($email, $password))) {
                throw new \Exception('Unauthorized', 401);
            }
            $this->sl->auth->setSessionVar('uid', $user->id);
            $this->sl->auth->setSessionVar('nonce', $this->sl->auth->makeToken());
            $response->setNonce($this->sl->auth->getSessionVar('nonce'));
        } catch (\Exception $e) {
            $response->setError($e->getMessage());
            $response->setCode($e->getCode());
        }

        return $response;
    }

    public function api() {
        $builder = $this->sl->create('apicall');
        return $builder->execute();
    }

}
