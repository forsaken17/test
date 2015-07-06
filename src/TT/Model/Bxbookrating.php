<?php

namespace TT\Model;

/**
 * Description of BxRating
 *
 * @author tt
 */
class Bxbookrating extends Entity {

    protected static $tableName = 'BX-Book-Ratings';
    protected $dataholder = [
        'User-ID' => '',
        'ISBN' => '',
        'Book-Rating' => '',
    ];

}
