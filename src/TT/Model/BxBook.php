<?php

namespace TT\Model;

/**
 * Description of Book
 *
 * @author tt
 */
class BxBook extends Entity {

    protected static $tableName = 'BX-Books';
    protected $dataholder = [
        'ISBN' => '',
        'Book-Title' => '',
        'Book-Author' => '',
        'Year-Of-Publication' => '',
        'Publisher' => '',
        'Image-URL-S' => '',
        'Image-URL-M' => '',
        'Image-URL-L' => '',
    ];

}
