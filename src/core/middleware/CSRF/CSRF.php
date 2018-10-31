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


function setCookieWithCSRFToken() {
    global $KEY_NAME;
    if (!isset($_SESSION[$KEY_NAME])) {
        setSessionCSRFToken();
    }
    setcookie($KEY_NAME, $_SESSION[$KEY_NAME]);
}

function setSessionCSRFToken() {
    global $KEY_NAME;
    $token = md5(uniqid(rand(), true));
    $_SESSION[$KEY_NAME] = $token;
    setCookieWithCSRFToken();
}

function protectMethod($method) {
    global $KEY_NAME;
    if($_SERVER['REQUEST_METHOD'] === $method) {
        if(!isset($_SESSION[$KEY_NAME]) || !isset($_COOKIE[$KEY_NAME])) {
            closeConnection( "csrf_token is not set.");
            setCookieWithCSRFToken();
        } elseif(($_COOKIE[$KEY_NAME] != $_SESSION[$KEY_NAME])) {
            closeConnection( "invalid csrf_token");
            setCookieWithCSRFToken();
        }
    }
}

function CSRF_TOKEN() {
    global $KEY_NAME;
    return $_SESSION[$KEY_NAME];
}

array_map('protectMethod', $PROTECTED_METHODS);

if(!isset($_COOKIE[$KEY_NAME])) {
    error_log("CSRF cookie was not set, setting new one");
    setCookieWithCSRFToken();
}