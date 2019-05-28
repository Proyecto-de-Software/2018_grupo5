<?php
/**
 * User: cristian
 * Date: 29/10/18
 * Time: 23:31
 */


class XSS implements Middleware {

    private static function html_scape(&$value) {
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    private static function filterArray($array) {
        array_walk_recursive($array, "html_scape");
        return $array;
    }

    function run() {
        $_POST = self::filterArray($_POST);
        $_GET = self::filterArray($_GET);
    }
}


