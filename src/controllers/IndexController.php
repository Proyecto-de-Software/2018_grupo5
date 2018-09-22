<?php
require_once(CODE_ROOT . "/controllers/Controller.php");
use controllers\Controller;

class IndexController extends Controller {

    public static function render(...$args){

        $instance = new IndexController();

		$parameters=array(
						'titulo' => 'Home', 
						'apellido' => 'Gonzalez',
                        'id' => ($args[0]['numero'] ?? 'None'),
					);

        return $instance->twig_render('index.html', $parameters);
    }

}


	