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

}
