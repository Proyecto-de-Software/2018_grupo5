<?php
require_once(CODE_ROOT . "/controllers/Controller.php");
use controllers\Controller;

class IndexController extends Controller {

    public function index(...$args){
        $this->assertInMaintenance();

        $parameters=array(
                        'titulo' => 'Home',
                        'apellido' => 'Gonzalez',
                        'id' => ($args[0]['numero'] ?? 'None'),
                    );

        return $this->twig_render('index.html', $parameters);
    }

}


