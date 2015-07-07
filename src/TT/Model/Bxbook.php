<?php

namespace TT\Model;

/**
 * Description of Book
 *
 * @author tt
 */
class Bxbook extends Entity {

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
    protected $mapping = [
        'id' => 'ISBN',
        'title' => 'Book-Title',
        'author' => 'Book-Author',
        'year' => 'Year-Of-Publication',
        'publisher' => 'Publisher',
    ];

}
