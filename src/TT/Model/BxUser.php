<?php

namespace TT\Model;

/**
 * Description of BxUser
 *
 * @author tt
 */
class BxUser extends Entity {

    protected static $tableName = 'BX-Users';
    protected $dataholder = [
        'User-ID' => '',
        'Location' => '',
        'Age' => '',
    ];

}
