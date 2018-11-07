<?php
require_once("Controller.php");
require_once(CODE_ROOT . "/models/Configuracion.php");

use controllers\Controller;

class ConfiguracionController extends Controller {
    private $confiDao;

    function __construct() {
        parent::__construct();
        $this->confiDao = new ConfiguracionDAO();
    }

    function indexView(...$args) {
        $this->assertPermission();
        $parameters = [
            'sitio_activo' => $this->confiDao->getConfigValue('sitio_activo'),
        ];
        return $this->twig_render("modules/configuracion/index.html", $parameters);
    }

    function update(...$args) {
        $this->assertPermission();
        $setttings = [
            'titulo' => $_POST['titulo'],
            'descripcion' => $_POST['descripcion'],
            'email_de_contacto' => $_POST['email'],
            'paginacion' => abs($_POST['paginacion']),
        ];
        foreach ($setttings as $name => $value) {
            $this->setSetting($name, $value);
        }
        $this->redirect('/modulo/configuracion');
    }

    private function setSetting($name, $value) {
        $config = $this->confiDao->getConfigByName($name);
        if($config === null) {
            $config = new Configuracion();
        }
        $config->setVariable($name);
        $config->setValor($value);
        $this->confiDao->persist($config);
    }


    function setMantenimiento(...$args) {
        $this->assertPermission();
        $this->setSetting('sitio_activo', (string)$_POST['sitio_activo']);
        return $this->jsonResponse([
            'ok' => true,
        ]);
    }

}