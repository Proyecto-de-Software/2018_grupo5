<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/08/18
 * Time: 18:46
 */
require_once('core/db/Model.php');

class Usuario_DB extends Model_DB {

    var $name;
    var $lastname;

    function init() {
        $this->name = (new Models())->setApiAccessible();
    }
}