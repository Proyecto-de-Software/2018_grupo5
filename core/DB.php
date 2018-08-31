<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/08/18
 * Time: 19:28
 */

class DB
{
    var $db;

    function __construct()

    {
        $servername = "localhost";
        $username = "username";
        $password = "password";
        $dbname = "myDBPDO";
        $this->db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    }

    function db() {
        return $this->db;
    }

}