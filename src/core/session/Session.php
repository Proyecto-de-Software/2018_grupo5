<?php

class Session {

    private $user_id;
    private $user_data;

    public function get_or_set($key, $value) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        $_SESSION[$key] = $value;
        return $value;
    }

    public function __construct() {
        $this->user_id = $this->get_or_set('USER_ID', null);
        $this->user_data = $this->get_or_set('USER_DATA', []);
        if ($this->user_id) {

        }

    }

    public function isAuthenticated() {
        return $this->user_id != null;
    }

    /**
     * Is to represent when a single user is authenticated
     * @param $user_id int
     * @param $user_data
     */
    public function createAuthenticatedSession($user_id, $user_data) {
        $_SESSION['USER_ID'] = $user_id;
        $_SESSION['USER_DATA'] = $user_data;
    }

    public function destroyAuthenticatedSession() {
        unset($_SESSION['USER_ID']);
        unset($_SESSION['USER_DATA']);
    }

    public function user() {
        return 342342;
    }

}