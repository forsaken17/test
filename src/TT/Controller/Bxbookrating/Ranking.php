<?php

namespace TT\Controller\Bxbookrating;

use TT\Controller\Rest;

/**
 *
 * @author tt
 */
class Ranking extends Rest {

    public function get() {
        $country = $this->request->get('country', FILTER_SANITIZE_STRING);
        $direction = $this->request->get('direction', FILTER_SANITIZE_STRING);
        $limit = $this->request->get('limit', FILTER_SANITIZE_NUMBER_INT);
        $offset = $this->request->get('offset', FILTER_SANITIZE_NUMBER_INT);
        $result = $this->dbm->findBookRatingByCountry($country, ['order' => 'rank', 'direction' => $direction], ['limit' => $limit, 'offset' => $offset]);
        $this->response = $result;
        $this->responseCode = 200;
    }

    public function post() {
        throw new \Exception('Unsupported HTTP method ' . $this->request->method, 405);
    }

    public function put() {
        throw new \Exception('Unsupported HTTP method ' . $this->request->method, 405);
    }

    public function delete() {
        throw new \Exception('Unsupported HTTP method ' . $this->request->method, 405);
    }

}
