<?php
require_once(CODE_ROOT . "/controllers/Controller.php");
require_once(CODE_ROOT . "/core/utils/twig/lib/Twig/Autoloader.php");
use controllers\Controller;

class IndexController extends Controller {

    public static function render(...$args){
        //echo "Hola mundo! esto es el index ";
        //var_dump($args);

        //Usando Twig, envio de parametros a archivo html dentro de folder "templates"
        Twig_Autoloader::register();	
        
        //Aca indicamos en que ruta se encuentra el html que va a recibir los parametros
		$loader = new Twig_Loader_Filesystem(CODE_ROOT . "/templates");

		$twig = new Twig_Environment($loader);
		$parameters=array(
						'nombre' => 'pepe', 
						'apellido' => 'Gonzalez'
					);
		echo $twig->render('pruebaTwig.html', $parameters);



    }

}


	