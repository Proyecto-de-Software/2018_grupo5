<?php
require_once(CODE_ROOT . "/controllers/Controller.php");
use controllers\Controller;

class IndexController extends Controller {

    public function index(...$args){
        $this->assertInMaintenance();
        return $this->twig_render('index.html', []);
    }

    public function contacto(...$args){
        $this->assertInMaintenance();
        return $this->twig_render('index.html', []);
    }
}


