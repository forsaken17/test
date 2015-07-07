<?php

namespace TT;

/**
 * Description of Response
 *
 * @author tt
 */
class Response {

    private $nonce;
    private $data = [];
    private $error = [];
    private static $type;
    private static $code = 200;
    private static $codes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    );
    private static $contentType = array(
        'json' => 'application/json',
        'html' => 'text/html'
    );

    public function __construct(Locator $sl, $type = 'html') {
        self::$type = $type;
    }

    public static function getContentType() {
        return self::$contentType[self::$type];
    }

    public static function getCode() {
        return self::$code;
    }

    public function setCode($code) {
        self::$code = $code;
    }

    public static function getStatusMessage($code) {
        return (isset(self::$codes[$code])) ? self::$codes[$code] : self::$codes[500];
    }

    public function getData() {
        return $this->data;
    }

    public function setData(array $data) {
        $this->data = $data;
    }

    public function setNonce($nonce) {
        $this->nonce = $nonce;
    }

    public function setError($msg) {
        return $this->error = $msg;
    }

    private function getJsonError() {
        return $this->error = json_last_error_msg();
    }

    public function getJson($body) {
        return json_encode($body);
    }

    public function __toString() {
        $string = '';

        $body = ['data' => $this->data, 'nonce' => $this->nonce, 'error' => $this->error];
        if ('json' === self::$type) {
            if (false === ($string = $this->getJson($body))) {
                $string = $this->getJson($this->getJsonError());
            }
        } else {
            $string = serialize($body);
        }
        return $string;
    }

}
