<?php
require_once("Controller.php");
require_once(CODE_ROOT . "/models/Configuracion.php");
use controllers\Controller;

class ConfiguracionController  extends Controller {

     function index(...$args) {
        $this->assertPermission();
        $parameters = [];
        return $this->twig_render("modules/configuracion/index.html", $parameters);
    }

    function update(...$args){
         $this->assertPermission();
         $setttings = [
             'titulo' => $_POST['titulo'],
             'descripcion' => $_POST['descripcion'],
             'email_de_contacto' => $_POST['email'],
             'paginacion' => $_POST['paginacion'],
         ];
         foreach ($setttings as $name=>$value){
             $this->setSetting($name, $value);
         }
         $this->redirect('/modulo/configuracion');
    }

    private function setSetting($name, $value){
        $config = $this->getModel('Configuracion')->findOneBy(array('variable'=> $name));
        if ($config === null){
            $config = new Configuracion();
        }
        $config->setVariable($name);
        $config->setValor($value);
        $this->entityManager()->persist($config);
        $this->entityManager()->flush();
    }

}