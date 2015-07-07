<?php

namespace TT\Controller;
use TT\Model\Bxbook as BookModel;
/**
 *
 * @author tt
 */
class Bxbook extends Rest {

    public function get() {
        $book = new BookModel();
        $book->id = $this->request->get('isbn', FILTER_SANITIZE_STRING);
        try {
            $new = $this->dbm->find($book);
        } catch (\Exception $exc) {
            throw new \Exception($exc->getTraceAsString(), 400);
        }
        $this->response = $new->getData();
        $this->responseCode = 200;
    }

    public function post() {
        $book = new BookModel();
        $book->id = $this->request->get('ISBN', FILTER_SANITIZE_STRING);
        $book->title = $this->request->get('Book-Title', FILTER_SANITIZE_STRING);
        $book->author = $this->request->get('Book-Author', FILTER_SANITIZE_STRING);
        $book->year = $this->request->get('Year-Of-Publication', FILTER_SANITIZE_STRING);
        $book->publisher = $this->request->get('Publisher', FILTER_SANITIZE_STRING);

        if(!$this->dbm->save($book)){
            throw new \Exception("Id: {$book->id} Not found", 400);
        }
        $this->response = [$book->id];
        $this->responseCode = 201;
    }

    public function put() {
        $book = new BookModel();
        $book->id = $this->request->get('ISBN', FILTER_SANITIZE_STRING);
        $book->title = $this->request->get('Book-Title', FILTER_SANITIZE_STRING);
        $book->author = $this->request->get('Book-Author', FILTER_SANITIZE_STRING);
        $book->year = $this->request->get('Year-Of-Publication', FILTER_SANITIZE_STRING);
        $book->publisher = $this->request->get('Publisher', FILTER_SANITIZE_STRING);

        if(!$this->dbm->save($book)){
            throw new \Exception("Id: {$book->id} Not found", 400);
        }
        $this->response = [$book->id];
        $this->responseCode = 200;
    }

    public function delete() {
        $this->response = ['DELETE'];
        $this->responseCode = 200;
    }

}
