<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 29/10/18
 * Time: 23:31
 */


class ProtectorCSRF {
    private $KEY_NAME = "csrf_token";
    private $PROTECTED_METHODS = ['POST', 'DEL'];

    function __construct() {
        $this->ensureCSRF();
    }

    private function getRandomToken() {
        return md5(uniqid(rand(), true));
    }

    function ensureCSRF() {
        if(!isset($_SESSION[$this->KEY_NAME]) || !isset($_COOKIE[$this->KEY_NAME])) {
            $this->setSessionCSRFToken();
        }
    }

    function closeConnection($msg = null) {
        http_response_code(403);
        die('Forbidden ' . $msg);
    }

    function setCookieWithCSRFToken() {
        setcookie($this->KEY_NAME, $_SESSION[$this->KEY_NAME], 0, "/");
    }

    function setSessionCSRFToken() {
        $token = $this->getRandomToken();
        unset($_SESSION[$this->KEY_NAME]);
        $_SESSION[$this->KEY_NAME] = $token;
        $this->setCookieWithCSRFToken();
    }

    private function aggressiveProtectRequestMethod() {
        $request_method_var = "\$_" . $_SERVER['REQUEST_METHOD'] . "['$this->KEY_NAME'];";

        if(!isset($_SESSION[$this->KEY_NAME]) || !isset($_COOKIE[$this->KEY_NAME])) {
            $msg = $this->KEY_NAME . " is not set.";

        } elseif(($_COOKIE[$this->KEY_NAME] == $_SESSION[$this->KEY_NAME]) ||
            eval($request_method_var) == $_SESSION[$this->KEY_NAME]) {
            /**@doc: Request is OK */
            return;
        } else {
            $msg = "invalid token in " . $this->KEY_NAME;
        }

        $this->setSessionCSRFToken();
        $this->closeConnection($msg);

    }

    function aggressiveProtectRequestMethods() {
        if(in_array($_SERVER['REQUEST_METHOD'], $this->PROTECTED_METHODS)) {
            $this->aggressiveProtectRequestMethod();
        }
    }

    function getCSRFToken() {
        $this->ensureCSRF();
        return $_SESSION[$this->KEY_NAME];
    }
}
