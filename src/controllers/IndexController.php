<?php
require_once(CODE_ROOT . "/controllers/Controller.php");
use controllers\Controller;

class IndexController extends Controller {

    public static function render(...$args){

        $instance = new IndexController();

		$parameters=array(
						'nombre' => 'pepe', 
						'apellido' => 'Gonzalez',
                        'id' => ($args[0]['identificador'] ?? 'None'),
					);

        echo $instance->twig_render('index.twig',$parameters);
    }

}


	