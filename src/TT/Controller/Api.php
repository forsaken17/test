<?php

namespace TT\Controller;

use TT\Request,
    TT\Response;

/**
 *
 * @author tt
 */
class Api extends Front {

    public function __construct(\TT\Locator $sl) {
        parent::__construct($sl);
    }

    public function api() {
        $response = new Response();
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
//        return ['some' => 'text', $modelName];
        try {
            if (null == $modelName) {
                throw new \Exception('Method not allowed', 405);
            }
            $controller = new \ReflectionClass('TT\\Controller\\' . ucfirst($modelName));
            if (!$controller->isInstantiable()) {
                throw new \Exception('Bad Request', 400);
            }
            $request = Request::instance();
            try {
                $method = $controller->getMethod($request->method);
            } catch (\ReflectionException $re) {
                throw new \Exception('Unsupported HTTP method ' . $request->method, 405);
            }
            if (!$method->isStatic()) {
                $controller = $controller->newInstance($request);
                if (!$controller->checkAuth()) {
                    throw new \Exception('Unauthorized', 401);
                }
                $method->invoke($controller);
                $data = $controller->getResponse();
                $responseStatus = $controller->getResponseStatus();
            } else {
                throw new \Exception('Static methods not supported in Controllers', 500);
            }
            if (is_null($data)) {
                throw new \Exception('Method not allowed', 405);
            }
        } catch (\Exception $re) {
            $responseStatus = $re->getCode();
            $data = array('ErrorCode' => $re->getCode(), 'ErrorMessage' => $re->getMessage());
        }
        return [$data, $responseStatus];
    }

}
