<?php

namespace TT\Model;

/**
 * Description of BxRating
 *
 * @author tt
 */
class BxBookRating extends Entity {

    protected static $tableName = 'BX-Book-Ratings';
    protected $dataholder = [
        'User-ID' => '',
        'ISBN' => '',
        'Book-Rating' => '',
    ];

}
