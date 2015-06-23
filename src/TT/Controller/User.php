<?php

namespace TT\Controller;

use TT\Model\User as UserModel;

/**
 *
 * @author tt
 */
class User extends Front {

    public function login() {
        $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($this->sl->auth->getToken() === $token) {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = sha1(filter_input(INPUT_POST, 'password') . SALT);
            try {
                if ($user = $this->dbm->findUserByEmailAndPassword($email, $password)) {
                    $this->sl->auth->setSessionVar('uid', $user->id);
                    \redirect(\url());
                }
            } catch (\Exception $exc) {
                $this->sl->auth->setSessionVar('error_msg', $exc->getMessage());
            }
        }
        return $this->sl->view->render('login', ['token' => $this->sl->auth->makeToken()]);
    }

    public function logout() {
        $this->sl->auth->endSession();
        \redirect(\url());
    }

    public function register() {
        $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($this->sl->auth->getToken() === $token) {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = sha1(filter_input(INPUT_POST, 'password') . SALT);
            $password2 = sha1(filter_input(INPUT_POST, 'password2') . SALT);
            try {
                $user = $this->dbm->findUserByEmail($email);
            } catch (\Exception $exc) {
                $user = false;
            }

            if ($password === $password2 && !$user) {
                $userNew = new UserModel();
                $userNew->email = $email;
                $userNew->sha1 = $password;
                $this->dbm->save($userNew);

                \redirect(\url());
            }
        }
        return $this->sl->view->render('register', ['token' => $this->sl->auth->makeToken()]);
    }

}
