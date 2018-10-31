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
        if(!isset($_SESSION[$this->KEY_NAME])) {
            error_log("CSRF cookie was not set, setting new one");
            $this->setSessionCSRFToken();
        }
    }

    function closeConnection($msg = null) {
        http_response_code(403);
        die('Forbidden ' . $msg);
    }

    function setCookieWithCSRFToken() {
        $this->ensureCSRF();
        setcookie($this->KEY_NAME, $_SESSION[$this->KEY_NAME], 0,"/");
    }

    function setSessionCSRFToken() {
        $token = $this->getRandomToken();
        $_SESSION[$this->KEY_NAME] = $token;
        $this->setCookieWithCSRFToken();
    }

    private function aggressiveProtectRequestMethod($method) {
        if($_SERVER['REQUEST_METHOD'] === $method) {
            if(!isset($_SESSION[$this->KEY_NAME]) || !isset($_COOKIE[$this->KEY_NAME])) {
                $this->closeConnection("csrf_token is not set.");
                $this->ensureCSRF();
            } elseif(($_COOKIE[$this->KEY_NAME] != $_SESSION[$this->KEY_NAME])) {
                $this->closeConnection("invalid csrf_token");
                $this->setCookieWithCSRFToken();
            }
        }
    }

    function aggressiveProtectRequestMethods(){
        array_map(array($this, 'aggressiveProtectRequestMethod'), $this->PROTECTED_METHODS);
    }

    private function getCSRFToken() {
        $this->ensureCSRF();
        return $_SESSION[$this->KEY_NAME];
    }
}
