<?php

namespace TT;

/**
 * basic session and auth handler
 *
 * @author tt
 */
class Auth {

    private $token;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function check() {
        $time = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_STRING);
        $cnonce = filter_input(INPUT_POST, 'cnonce', FILTER_SANITIZE_STRING);
        $hash = filter_input(INPUT_POST, 'hash', FILTER_SANITIZE_STRING);
        $id = $this->getSessionVar('uid');
        $nonce = $this->getSessionVar('nonce'); //get last nonce for uid
        $this->setSessionVar('nonce', null); //remove old nonce
        $testHash = hash('sha1', $cnonce . $time . $nonce);
        if (time() > $time) {
            $isValidTime = false;
        }
        return $isValidTime && ($testHash == $hash);
    }

    /**
     *
     * @return mixed
     */
    public function isSignedIn() {
        return empty($_SESSION['uid']) ? false : $_SESSION['uid'];
    }

    /**
     *
     * @param type $action
     * @return bool
     */
    public function anonymAccess($action) {
        return !empty($action['anonymity']);
    }

    /**
     *
     * @return string
     */
    public function makeToken() {
        return $this->token = $_SESSION['token'] = md5(uniqid('auth', true));
    }

    /**
     *
     * @return mixed
     */
    public function getToken() {
        return empty($_SESSION['token']) ? false : $this->token = $_SESSION['token'];
    }

    /**
     *
     * @param type $key
     * @param type $value
     */
    public function setSessionVar($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     *
     * @param type $key
     * @return type
     */
    public function getSessionVar($key) {
        return empty($_SESSION[$key]) ? false : $_SESSION[$key];
    }

    /**
     *
     */
    public function endSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
    }

}
