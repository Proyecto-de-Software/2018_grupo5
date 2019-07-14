<?php

require_once("Controller.php");
use controllers\Controller;

class InstitucionController extends Controller {

    /** @var InstitucionDAO $institucionDao */
    private $institucionDao;

    function __construct() {
        parent::__construct();
        $this->institucionDao = new InstitucionDAO();
    }

    public function update($urlData) {
        // Lo malo de hacerlo asi, es que no se mapea automaticamente
        // la requests con el objeto.
        $this->assertPermissionJson();
        $institucion = $this->institucionDao->getById($urlData['id']);
        return $this->createOrUpdate($institucion);
    }

    public function create() {
        $this->assertPermissionJson();
        $institucion = new Institucion();
        return $this->createOrUpdate($institucion);
    }

    private function setValuesFromRequest(&$institucion) {
        #TODO validar los datos que vienen
        $tipoInstitucionDao = new TipoInstitucionDao();
        $regionSanitariaDao = new RegionSanitariaDAO();
        $institucion->setNombre($_POST['nombre']);
        $institucion->setDirector($_POST['director']);
        $institucion->setTelefono($_POST['telefono']);
        $institucion->setTipoInstitucion($tipoInstitucionDao->getById($_POST['idTipoInstitucion']));
        $institucion->setRegionSanitaria($regionSanitariaDao->getById($_POST['idRegionSanitaria']));
        $institucion->setCoordenadas($_POST['coordenadas']);
        $institucion->setDireccion($_POST['direccion']);
    }


    private function createOrUpdate($institucion) {
        $response = [
            'status' => 'ok'
        ];
        try {
            $this->setValuesFromRequest($institucion);
            $this->institucionDao->persist($institucion);
        } catch (Exception $e) {
            $response['status'] = 'failed';

        }
        return $this->jsonResponse($response);
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
        if ($institucion != null) {
            return $this->jsonResponse($institucion->jsonSerialize());
        } else {
            return null;
        }
    }

    public function getInstitucionesByRegionAsJSON($data) {
        $id_region = $data['id'];
        $json_instituciones = [];

        $instituciones = $this->institucionDao->findByRegionSanitariaId($id_region);
        foreach ($instituciones as $institucion) {
            $json_instituciones[] = $institucion->jsonSerialize();
        }
        return $this->jsonResponse($json_instituciones);
    }
}