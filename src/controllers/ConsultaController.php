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
require_once(CODE_ROOT . "/Dao/PacienteDAO.php");
require_once(CODE_ROOT . "/Dao/InstitucionDAO.php");

use controllers\Controller;

class ConsultaController extends Controller {


    public function create() {
        ////////////////faltan las validaciones por not nulls y devolver error si corresponde, como en PacienteController/////////////////////////
        ////////////////faltan las validaciones por not nulls y devolver error si corresponde, como en PacienteController/////////////////////////
        ////////////////faltan las validaciones por not nulls y devolver error si corresponde, como en PacienteController/////////////////////////
        ////////////////faltan las validaciones por not nulls y devolver error si corresponde, como en PacienteController/////////////////////////
        ////////////////faltan las validaciones por not nulls y devolver error si corresponde, como en PacienteController/////////////////////////
        $response = [
            'code' => -1,
            'msg' => null,
            'error' => true,
        ];

        try {
            $consulta = new Consulta();
            $this->setConsultaFromRequest($consulta);
            $consultaDao = new ConsultaDAO();
            $consultaDao->persist($consulta);
            $response['code'] = 0;
            $response['msg'] = "Consulta agregada";
            $response['id'] = $consulta->getId();
            $response['error'] = false;
        } catch (Exception $e) {
            $response["msg"] = "Error" . $e->getMessage();
            $response['code'] = 2;
        }

        return $this->jsonResponse($response);
    }

    private function setConsultaFromRequest(&$consultaInstance) {

        $pacienteDao = new PacienteDAO();
        $paciente = $pacienteDao->getById($_POST['paciente_id']);
        $consultaInstance->setPaciente($paciente);

        $datetime= new DateTime($_POST['fecha_consulta']); // Mandatory for doctrine
        $consultaInstance->setFecha($datetime);

        $motivoDao = new MotivoConsultaDAO();
        $motivo = $motivoDao->getById($_POST['motivo']);
        $consultaInstance->setMotivo($motivo);

        $institucionDao = new InstitucionDAO();
        $derivacion =$institucionDao->getById($_POST['derivacion']);
        $consultaInstance->setDerivacion($derivacion);

        $internacion_value = isset($_POST['internacion']) ? '1' : '0'; // Checkbox form input
        $consultaInstance->setInternacion($internacion_value);

        $tratamiento_farmacologico_dao = new TratamientoFarmacologicoDAO();
        $tratamiento_farmacologico = $tratamiento_farmacologico_dao->getById($_POST['tratamiento_farmacologico']);
        $consultaInstance->setTratamientoFarmacologico($tratamiento_farmacologico);

        $acompanamientoDao = new AcompaniamientoDAO();
        $acompanamiento = $acompanamientoDao->getById($_POST['acompanamiento']);
        $consultaInstance->setAcompanamiento($acompanamiento);

        $consultaInstance->setArticulacionConInstituciones($_POST['articulacion']);
        $consultaInstance->setDiagnostico($_POST['diagnostico']);
        $consultaInstance->setObservaciones($_POST['observaciones']);

    }

    public function createView() {
        $motivosDao = new MotivoConsultaDAO();
        $acompanamientosDao = new AcompaniamientoDAO();
        $tratamientoFarmacologicoDAO = new TratamientoFarmacologicoDAO();
        $parameters = [
            'motivos' => $motivosDao->getAll(),
            'acompanamientos' => $acompanamientosDao->getAll(),
            'tratamientos_farmacologicos' => $tratamientoFarmacologicoDAO->getAll(),
        ];

        return $this->twig_render("modules/consultas/formConsulta.html", $parameters);
    }
}

