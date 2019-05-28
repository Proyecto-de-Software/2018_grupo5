<?php
/**
 * User: cristian
 * Date: 29/10/18
 * Time: 23:31
 */


class XSS implements Middleware {

    private function html_scape(&$value) {
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    private  function filterArray($array) {
        array_walk_recursive($array, [$this, "html_scape"]);
        return $array;
    }

    function run() {
        $_POST = $this->filterArray($_POST);
        $_GET = $this->filterArray($_GET);
    }
}


