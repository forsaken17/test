<?php

namespace TT\Controller;

/**
 *
 * @author tt
 */
class Api extends Front {

    public function __construct(\TT\Locator $sl) {
        parent::__construct($sl);
    }

    public function api() {
        $response = new \TT\Response();
        if ($auth = $this->sl->auth->check() && $modelName = \TT\Router::$apiAction) {
            $response->setData($this->makeCall($modelName));
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = sha1(filter_input(INPUT_POST, 'password') . SALT);
        if ($email && $password) {
            try {
                if ($user = $this->dbm->findUserByEmailAndPassword($email, $password)) {
                    $this->sl->auth->setSessionVar('uid', $user->id);
                    $this->sl->auth->setSessionVar('nonce', $this->sl->auth->makeToken());
                    $response->setData(['nonce' => $this->sl->auth->getSessionVar('nonce')]);
                    $auth = true;
                }
            } catch (\Exception $exc) {
                $this->sl->auth->setSessionVar('error_msg', $exc->getMessage());
                $auth = false;
            }
        }
        if (!$auth) {
            $response->addError('auth failed');
        }
        return $response;
    }

    private function makeCall($modelName) {
        return ['some' => 'text', $modelName];
    }

}
