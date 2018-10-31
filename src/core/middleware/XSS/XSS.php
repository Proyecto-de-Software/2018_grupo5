<?php
/**
 * User: cristian
 * Date: 29/10/18
 * Time: 23:31
 */

$_POST = array_map("htmlspecialchars", $_POST);
$_GET = array_map("htmlspecialchars", $_GET);
