<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/08/18
 * Time: 18:46
 */
require_once('../core/Models.php');
require_once('../core/Model.php');


class User extends Model {

    var $name;

    function init() {
        parent::__construct();
        $this->name = (new Models())->setApiAccessible();
    }

    function get_table_name() {
        return "usuario";
    }

}
