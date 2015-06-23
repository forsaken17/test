<?php

namespace TT\Model;

/**
 *
 * @author tt
 */
class User extends Entity {

    protected static $tableName = 'user';
    protected $dataholder = [
        'id' => '',
        'email' => '',
        'sha1' => '',
        'admin' => '',
    ];

    public function __construct(array $data = null) {
        if (null !== $data) {
            $this->dataholder = $data;
        }
    }

}
