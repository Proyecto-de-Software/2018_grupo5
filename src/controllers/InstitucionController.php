<?php

require_once(CODE_ROOT . "/controllers/Controller.php");
require_once(CODE_ROOT . "/models/Institucion.php");
require_once(CODE_ROOT . "/Dao/InstitucionesDAO.php");
use controllers\Controller;


class InstitucionController extends Controller {

    /** @var InstitucionDAO $institucionDao */
    private $institucionDao;

    function __construct() {
        parent::__construct();
        $this->institucionDao = InstitucionDAO::getInstance();

    }

    public function getInstitucionesAsJSON() {
        /** @var  $json_instituciones */
        $json_instituciones = [];
        foreach ($this->institucionDao->getAll() as $institucion) {
            $json_instituciones[] = $institucion->jsonSerialize();
        }
        return $this->jsonResponse($json_instituciones);
    }


    public function getInstitucionAsJSON($data) {
        $id = $data['id'];
        $institucion = $this->institucionDao->getById($id);
        if ($institucion != null)
            return $this->jsonResponse($institucion->jsonSerialize());
        else
            return null;
    }

    public function getInstitucionesByRegionAsJSON($data) {
        $id_region = $data['id'];
        $json_instituciones = [];

        $instituciones = $this->institucionDao->findByRegionSantiariaId($id_region);
        foreach ($instituciones as $institucion) {
            $json_instituciones[] = $institucion->jsonSerialize();
        }
        return $this->jsonResponse($json_instituciones);
    }
}