<?php

namespace TT\Controller;

use TT\Request,
    TT\Response,
    TT\Router;

/**
 *
 * @author tt
 */
class Api extends Front {

    public function __construct(\TT\Locator $sl) {
        parent::__construct($sl);
    }

    public function auth() {
        $response = new Response('json');
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = sha1(filter_input(INPUT_POST, 'password') . SALT);
        try {
            if (!($user = $this->dbm->findUserByEmailAndPassword($email, $password))) {
                throw new \Exception('Unauthorized', 401);
            }
            $this->sl->auth->setSessionVar('uid', $user->id);
            $this->sl->auth->setSessionVar('nonce', $this->sl->auth->makeToken());
            $response->setData(['nonce' => $this->sl->auth->getSessionVar('nonce')]);
        } catch (\Exception $e) {
            $response->setError($e->getMessage());
            $response->setCode($e->getCode());
        }

        return $response;
    }

    public function api() {
        $response = new Response('json');
        try {
            if (null === ($modelName = \TT\Router::$apiAction)) {
                throw new \Exception('Method not allowed', 405);
            }
            $actionParams = Router::getActionParams($modelName);
            if (!$this->sl->auth->check() && !$this->sl->auth->anonymAccess($actionParams)) {
                throw new \Exception('Unauthorized', 401);
            }
            $path = '';
            foreach ($actionParams['resource'] as $part){
                $path .= '\\'.ucfirst($part);
            }
            $controller = new \ReflectionClass('TT\\Controller' . $path);
            if (!$controller->isInstantiable()) {
                throw new \Exception('Bad Request', 400);
            }
            $request = Request::instance();
            try {
                $method = $controller->getMethod($request->method);
            } catch (\ReflectionException $e) {
                throw new \Exception('Unsupported HTTP method ' . $request->method, 405);
            }
            if (!$method->isStatic()) {
                $controller = $controller->newInstance($request);
                if (!$controller->checkUserPermission()) {
                    throw new \Exception('Unauthorized', 401);
                }
                $method->invoke($controller);
                $data = $controller->getResponse();
                $code = $controller->getResponseCode();
            } else {
                throw new \Exception('Static methods not supported in Controllers', 500);
            }
            if (is_null($data)) {
                throw new \Exception('Method not allowed', 405);
            }
            $this->sl->auth->setSessionVar('nonce', $nonce = $this->sl->auth->makeToken());
            $data['nonce'] = $nonce;
            $response->setCode($code);
            $response->setData($data);
        } catch (\Exception $e) {
            $response->setError($e->getMessage());
            $response->setCode($e->getCode());
        }

        return $response;
    }

}
