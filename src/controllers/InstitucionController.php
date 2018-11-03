<?php

require_once(CODE_ROOT . "/controllers/Controller.php");
require_once(CODE_ROOT . "/models/Institucion.php");
require_once(CODE_ROOT . "/Dao/InstitucionesDAO.php");
use controllers\Controller;


class InstitucionController extends Controller {

    public function getInstitucionesAsJSON() {
        $institucionesDAO = new InstitucionesDAO();
        $json_instituciones = [];
        foreach ($institucionesDAO->getAll() as $institucion) {
            $json_instituciones[] = $institucion->jsonSerialize();
        }
        return $this->jsonResponse($json_instituciones);
    }


    public function getInstitucionAsJSON($data) {
        $id = $data['id'];
        $institucionesDAO = new InstitucionesDAO();
        $institucion = $institucionesDAO->getModel();
        $institucion = $institucion->findOneBy(['id' => $id]);
        if ($institucion != null)
            return $this->jsonResponse($institucion->jsonSerialize());
        else
            return null;
    }

    public function getInstitucionesByRegionAsJSON($data) {
        $id_region = $data['id'];
        $json_instituciones = [];
        $institucionesDAO = new InstitucionesDAO();
        $instituciones = $institucionesDAO->getModel()->findBy(['regionSanitaria' => $id_region]);
        foreach ($instituciones as $institucion) {
            $json_instituciones[] = $institucion->jsonSerialize();
        }
        return $this->jsonResponse($json_instituciones);
    }
}