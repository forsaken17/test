<?php

namespace TT;

/**
 * simple registry
 *
 * @author user
 */
class Locator extends \ArrayObject {

    private $instances = [];
    private $mapping = [
        'router' => '\\TT\\Router',
        'request' => '\\TT\\Request',
        'response' => '\\TT\\Response',
        'apicall' => '\\TT\\ApiCallBuilder',
    ];

    public function __construct() {
        parent::__construct([]);
    }

    private static $me;

    /**
     *
     * @return self
     */
    public static function instance() {
        if (null === self::$me) {
            self::$me = new self();
        }
        return self::$me;
    }

    public function create() {
        $args = func_get_args();
        $class = array_shift($args);

        if (!array_key_exists($class, $this->mapping)) {
            throw new \Exception("class $class is not registered");
        }
        $className = $this->mapping[$class];

        if (!isset($this->instances[$className]) || !is_a($this->instances[$className], $className)) {
            array_unshift($args, self::$me);
            $reflection = new \ReflectionClass($className);
            $this->instances[$className] = $reflection->newInstanceArgs($args);
        }
        return $this->instances[$className];
    }

    public function register($name, $object) {
        $this->mapping[$name] = get_class($object);
        $this->instances[$this->mapping[$name]] = $object;
    }

    public function __get($name) {
        return $this->create($name);
    }

    public function __set($name, $object) {
        $this->register($name, $object);
    }

}
