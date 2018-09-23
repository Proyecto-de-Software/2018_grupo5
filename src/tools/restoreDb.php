<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 22/09/18
 * Time: 22:40
 */

include_once ("../bootstrap.php");

$db = SETTINGS['database']['dbname'];
$usr = SETTINGS['database']['user'];
$pwd = SETTINGS['database']['password'];
$cmd = "mysql ". $db . " -u" . $usr . " -p" . $pwd ." < ../sql/grupo5.sql";
echo exec($cmd);