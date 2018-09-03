<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/08/18
 * Time: 18:46
 */
require_once('../core/Models.php');
require_once('../core/Model.php');


class User_DB extends Model_DB {

    var $name;
    var $lastname;
    
    function init() {
        $this->name = (new Models())->setApiAccessible();
    }

    public  function get_table_name() {
        return "usuario";
    }

}
