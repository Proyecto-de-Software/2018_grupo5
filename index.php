<?php

require_once("models/Usuario.php");

$user = new User_DB();

$datos = array( 'apellido' => 'sdajfklsjfadlkfjkdas',);

$isnta_user = User_DB::create();


$user->objects->create($datos);

$users=$user->objects->get_by_id("1");
echo $users[3]->name;
