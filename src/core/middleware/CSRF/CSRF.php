<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 29/10/18
 * Time: 23:31
 */

$PROTECTED_METHODS = ['POST', 'DEL'];
$KEY_NAME = "csrf_token";

function closeConnection($msg = null) {
    http_response_code(403);
    die('Forbidden ' . $msg);
}

function setToken() {
    global $KEY_NAME;
    $token = md5(uniqid(rand(), true));
    setcookie($KEY_NAME, $token);
    $_SESSION[$KEY_NAME] = $token;
}

function protectMethod($method) {
    global $KEY_NAME;
    if($_SERVER['REQUEST_METHOD'] === $method) {
        if(!isset($_SESSION[$KEY_NAME]) || !isset($_COOKIE[$KEY_NAME])) {
            setToken();
            closeConnection( "csrf_token is not set.");
        } elseif(($_COOKIE[$KEY_NAME] != $_SESSION[$KEY_NAME])) {
            setToken();
            closeConnection( "invalid csrf_token");
        }
    }
}

function CSRF_TOKEN() {
    global $KEY_NAME;
    return $_SESSION[$KEY_NAME];
}

function setTokenIfNeeded() {
    global $KEY_NAME;
    if (!isset($_COOKIE[$KEY_NAME])){
        setToken();
    }
}

array_map('protectMethod', $PROTECTED_METHODS);
setTokenIfNeeded();