<?php

namespace TT\Controller;

use TT\Locator;

/**
 * base controller 
 *
 * @author tt
 */
class Front {

    /**
     *
     * @var \TT\Model\Manager
     */
    public $dbm;

    public function __construct(Locator $sl) {
        $this->sl = $sl;
        $this->dbm = $this->sl->dbm;
    }

}
