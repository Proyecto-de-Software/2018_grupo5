<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/08/18
 * Time: 18:46
 */

class User extends Model
{

    var $name;
    var $username;
    var $password;

    function __construct()
    {
        parent::__construct();
        $name = (new Models())->setApiAccessible();


    }

    function get_table_name()
    {
        return "usuario";
    }


}