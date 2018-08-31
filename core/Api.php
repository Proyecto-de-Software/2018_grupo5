<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/08/18
 * Time: 18:21
 */

abstract class Api
{

    public function get_param($param) {
        $method = $_SERVER['REQUEST_METHOD'];
        return eval('$_' . $method . '[' . $param . ']');
    }

    public function get_params() {
        $method = $_SERVER['REQUEST_METHOD'];
        return eval('$_' . $method);
    }

    public function validate_parameters($param) {
        $method = $_SERVER['REQUEST_METHOD'];
    }

    function run(){
        $action = $this->get_param('action');
        return eval('$this->action_' . $action);
    }

}