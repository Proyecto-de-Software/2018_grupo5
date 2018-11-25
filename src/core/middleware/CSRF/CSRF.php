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
        $this->ensureCSRFOnSession();
        $this->setCookieWithCSRFToken();
    }

    private function getRequestContent() {
        $content = null;
        if(0 === strlen(trim($content = file_get_contents('php://input')))) {
          $content = null;
        }
        return $content;
    }

    private function getRandomToken() {
        return md5(uniqid(rand(), true));
    }

    function ensureCSRFOnSession() {
        if(!isset($_SESSION[$this->KEY_NAME])) {
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

    private function validateCSRFToken() {
        parse_str($this->getRequestContent(), $request_vars );

        if(!isset($_SESSION[$this->KEY_NAME])) {
            $msg = "CSRF is not set in this session";
        } elseif (!isset($_COOKIE[$this->KEY_NAME]) && !isset($request_vars[$this->KEY_NAME])) {
            $msg = $this->KEY_NAME . " is not set.";
        } elseif (isset($_COOKIE[$this->KEY_NAME]) && $_COOKIE[$this->KEY_NAME] == $_SESSION[$this->KEY_NAME]) {
            /**@doc: Request is valid */
            return;
        } elseif($request_vars[$this->KEY_NAME] == $_SESSION[$this->KEY_NAME]) {
            /**@doc: Request is valid */
            return;
        } else {
            $msg = "invalid token in " . $this->KEY_NAME;
        }

        $this->setSessionCSRFToken();
        $this->closeConnection($msg);

    }

    function aggressiveProtectRequestMethods() {
        if(in_array($_SERVER['REQUEST_METHOD'], $this->PROTECTED_METHODS)) {
            $this->validateCSRFToken();
        }
    }

    function getCSRFToken() {
        $this->ensureCSRFOnSession();
        return $_SESSION[$this->KEY_NAME];
    }
}
