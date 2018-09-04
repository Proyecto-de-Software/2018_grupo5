<?php

require_once("models/User.php");

$user = new User_DB();

$datos = array( 'name' => 'el nombre');

$user->objects->create($datos);
$users=$user->objects->get_by_id("1");
echo $users[3]->name;
