<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 27/10/18
 * Time: 17:56
 */
require_once(CODE_ROOT . "/controllers/Controller.php");
require_once(CODE_ROOT . "/models/Institucion.php");
require_once(CODE_ROOT . "/Dao/InstitucionesDAO.php");
use controllers\Controller;


class InstitucionController extends Controller {

	public function getInstitucionesAsJSON(){
		$json_instituciones=array();

		$institucionesDAO =  new InstitucionesDAO();
        $instituciones = $institucionesDAO->getAll();

        foreach ($instituciones as $institucion) {
            array_push($json_instituciones, $institucion->jsonSerialize());
        }
        return json_encode($json_instituciones);
	}


	public function getInstitucionAsJSON($data){
		$id=$data['id'];
		$institucionesDAO =  new InstitucionesDAO();
        $institucion = $institucionesDAO->getModel();
        $institucion=$institucion->findOneBy(array('id' => $id));
		$json_institucion=$institucion->jsonSerialize();
		return json_encode($json_institucion);
	}


	public function getInstitucionesByRegionAsJSON($data){
		$id_region=$data['id'];
		$json_instituciones=array();
		$institucionesDAO =  new InstitucionesDAO();
        $instituciones = $institucionesDAO->getModel()->findBy( array('regionSanitaria' => $id_region) );
        foreach ($instituciones as $institucion) {
            array_push($json_instituciones, $institucion->jsonSerialize());
        }
        return json_encode($json_instituciones);

	}
	
}