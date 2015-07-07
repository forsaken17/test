<?php

namespace TT;

use TT\Locator;

/**
 * Description of ApiCallBuilder
 *
 * @author tt
 */
class ApiCallBuilder implements Injectable {

    public function __construct(Locator $sl) {
        $this->sl = $sl;
    }

    public function execute() {
        $response = $this->sl->create('response', 'json');
        try {
            $controller = $this->makeInstance();
            $data = $controller->getResponse();
            $code = $controller->getResponseCode();
            if (empty($data)) {
                throw new \Exception('Method not allowed', 405);
            }
            $response->setData($data);
        } catch (\PDOException $e) {
            $response->setError($e->getMessage());
            $code = 500;
        } catch (\Exception $e) {
            $response->setError($e->getMessage());
            $code = $e->getCode() ? $e->getCode() : 500;
        }
        $response->setCode($code);
        $this->sl->auth->setSessionVar('nonce', $nonce = $this->sl->auth->makeToken());
        $response->setNonce($nonce);
        return $response;
    }

    public function makeInstance() {
        $controller = new \ReflectionClass($this->getClass());
        if (!$controller->isInstantiable()) {
            throw new \Exception('Bad Request', 400);
        }
        $request = $this->sl->request;
        try {
            $method = $controller->getMethod($request->method);
        } catch (\ReflectionException $e) {
            throw new \Exception('Unsupported HTTP method ' . $request->method, 405);
        }
        if (!$method->isStatic()) {
            $controller = $controller->newInstance($this->sl);
            $method->invoke($controller);
        } else {
            throw new \Exception('Static methods not supported in Controllers', 500);
        }
        return $controller;
    }

    public function getClass() {
        $router = $this->sl->router;
        if (null === ($modelName = $router->getApiAction())) {
            throw new \Exception('Method not allowed', 405);
        }
        $actionParams = $router->getActionParams($modelName);
        if (!$this->sl->auth->check() && !$this->sl->auth->anonymAccess($actionParams)) {
            throw new \Exception('Unauthorized', 401);
        }
        $path = '';
        if (!is_array($actionParams['resource'])) {
            $path .= '\\' . ucfirst($actionParams['resource']);
        } else {
            foreach ($actionParams['resource'] as $part) {
                $path .= '\\' . ucfirst($part);
            }
        }
        return 'TT\\Controller' . $path;
    }

}
