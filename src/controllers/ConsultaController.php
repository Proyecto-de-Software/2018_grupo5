<?php
//
require_once(CODE_ROOT . "/controllers/Controller.php");
require_once(CODE_ROOT . "/models/Consulta.php");
require_once(CODE_ROOT . "/models/MotivoConsulta.php");
require_once(CODE_ROOT . "/models/Acompanamiento.php");
require_once(CODE_ROOT . "/models/Paciente.php");
require_once(CODE_ROOT . "/models/Institucion.php");

require_once(CODE_ROOT . "/models/TratamientoFarmacologico.php");
require_once(CODE_ROOT . "/Dao/TratamientoFarmacologicoDAO.php");
require_once(CODE_ROOT . "/Dao/MotivoConsultaDAO.php");
require_once(CODE_ROOT . "/Dao/AcompaniamientoDAO.php");
require_once(CODE_ROOT . "/Dao/ConsultaDAO.php");

use controllers\Controller;

class ConsultaController extends Controller {






    public function create(){
        $consulta = new Consulta();
        try {
                $this->entityManager()->persist($this->setConsulta($consulta));
                $this->entityManager()->flush();
                $response['code'] = 0;
                $response['msg'] = "Consulta agregada";
                $response['id'] = $consulta->getId();
                $response['error'] = false;
            } catch (Exception $e) {
                $response["msg"] = "Error" . $e->getMessage();
                $response['code'] = 2;
                $response['error'] = true;
            }
            return $this->jsonResponse($response);
    }

    private function setConsulta($consultaInstance){
        $paciente = $this->getModel('Paciente')->findOneBy(['id' => $_POST['paciente_id']]);
        $consultaInstance->setPaciente($paciente);
        //Conversion a tipo Date, exigencia de doctrine para insertar
        $dateConversion = new DateTime($_POST['fecha_consulta']);
        $consultaInstance->setFecha($dateConversion);
        $motivo = $this->getModel('MotivoConsulta')->findOneBy(['id' => $_POST['motivo']]);
        $consultaInstance->setMotivo($motivo);
        $derivacion = $this->getModel('Institucion')->findOneBy(['id' => $_POST['derivacion']]);
        $consultaInstance->setDerivacion($derivacion);
        $consultaInstance->setArticulacionConInstituciones($_POST['articulacion']);
        //Los checkbox vienen sin setear cuando no son tildados en los formularios, por eso tenemos que hacer este chequeo..
        isset($_POST['internacion']) ?  $consultaInstance->setInternacion('1') :  $consultaInstance->setInternacion('0');
        $consultaInstance->setDiagnostico($_POST['diagnostico']);
        $consultaInstance->setObservaciones($_POST['observaciones']);
        $tratamiento_farmacologico = $this->getModel('TratamientoFarmacologico')->findOneBy(['id' => $_POST['tratamiento_farmacologico']]);
        $consultaInstance->setTratamientoFarmacologico($tratamiento_farmacologico);
         $acompanamiento = $this->getModel('Acompanamiento')->findOneBy(['id' => $_POST['acompanamiento']]);
        $consultaInstance->setAcompanamiento($acompanamiento);
        return $consultaInstance;
    }

    public function createView(){
        $motivosDao = new MotivoConsultaDAO();
        $acompanamientosDao = new AcompaniamientoDAO();
        $tratamientoFarmacologicoDAO =  new TratamientoFarmacologicoDAO();
        $parameters = [
            'motivos' => $motivosDao->getAll(),
            'acompanamientos' => $acompanamientosDao->getAll(),
            'tratamientos_farmacologicos' => $tratamientoFarmacologicoDAO->getAll(),
        ];

        return $this->twig_render("modules/consultas/formConsulta.html", $parameters);
    }
}

