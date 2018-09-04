<?php

require_once("models/User.php");

$user = new User_DB();

$datos = array( 'name' => 'el nombre');

$user->objects->create($datos);