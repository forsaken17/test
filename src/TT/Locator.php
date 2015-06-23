<?php

namespace TT;

/**
 * simple registry
 *
 * @author user
 */
class Locator extends \ArrayObject {

    public function __construct() {
        parent::__construct([]);
    }

    private static $me;

    public static function instance() {
        if (null === self::$me) {
            self::$me = new self();
        }
        return self::$me;
    }

}
