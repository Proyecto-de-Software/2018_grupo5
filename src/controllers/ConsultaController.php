<?php
//
require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class ConsultaController extends Controller {

    public function index($param, $context = null) {
        /**@doc: Show Consultas of One patient */
        $consultaDao = new ConsultaDAO();
        $pacienteDao = new PacienteDAO();
        $paciente = $pacienteDao->getById($param[1]);
        $consultas = $consultaDao->getConsultasByPaciente($paciente);
        $context['consultas'] = $consultas;
        $context['paciente'] = $paciente;
        return $this->twig_render("modules/consultas/index.html", $context);
    }

    public function view($param) {
        /**@doc: view data of one Consulta */
        $id_consulta = $param['id'];
        $consultaDao = new ConsultaDAO();
        $parameter['consulta'] = $consultaDao->getById($id_consulta);
        return $this->twig_render("modules/consultas/view.html", $parameter);
    }

    public function updateView($param) {
        /**@doc: view for update one Consulta */
        $id_consulta = $param['id'];
        $consultaDao = new ConsultaDAO();
        $consulta = $consultaDao->getById($id_consulta);

        return $this->twig_render("modules/consultas/index.html", []);
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

    private function notNulls() {
        //Cargo los campos que no pueden ser nulos a un array para validar despues
        $notNulls = [
            "paciente_id",
            "fecha_consulta",
            "motivo",
            "derivacion",
            "diagnostico",
        ];
        return $notNulls;
    }

    public function validarFecha($unaFecha) {
        if(sizeof(explode("-", $unaFecha)) == 3) {
            $dia = explode("-", $unaFecha)[0];
            $mes = explode("-", $unaFecha)[1];
            $ano = explode("-", $unaFecha)[2];
            if(($dia !== "") && ($mes !== "") && ($ano !== "") && (strlen($ano) === 4)) {
                return checkdate($mes, $dia, $ano);
            }

        }
        return false;
    }

    public function create() {

        //$this->assertPermission();
        if(!$this->validarFecha($_POST['fecha_consulta'])) {
            $response['error'] = true;
            $response['code'] = 2;
            $response['msg'] = "La fecha de consulta ingresada no es correcta.";
            return $this->jsonResponse($response);
        }

        if($this->validateParams($this->notNulls())) {
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
        } else {
            $response = [
                'code' => -1,
                'msg' => "Faltan completar algunos datos obligatorios.",
                'error' => true,
            ];
        }
        return $this->jsonResponse($response);
    }

    private function setConsultaFromRequest(&$consultaInstance) {

        $pacienteDao = new PacienteDAO();
        $paciente = $pacienteDao->getById($_POST['paciente_id']);
        $consultaInstance->setPaciente($paciente);

        $datetime = new DateTime($_POST['fecha_consulta']); // Mandatory for doctrine
        $consultaInstance->setFecha($datetime);

        $motivoDao = new MotivoConsultaDAO();
        $motivo = $motivoDao->getById($_POST['motivo']);
        $consultaInstance->setMotivo($motivo);

        $institucionDao = new InstitucionDAO();
        $derivacion = $institucionDao->getById($_POST['derivacion']);
        $consultaInstance->setDerivacion($derivacion);

        $internacion_value = isset($_POST['internacion']) ? '1' : '0'; // Checkbox form input
        $consultaInstance->setInternacion($internacion_value);

        if(isset($_POST['tratamiento_farmacologico'])) {
            $tratamiento_farmacologico_dao = new TratamientoFarmacologicoDAO();
            $tratamiento_farmacologico = $tratamiento_farmacologico_dao->getById($_POST['tratamiento_farmacologico']);
            $consultaInstance->setTratamientoFarmacologico($tratamiento_farmacologico);
        }

        if(isset($_POST['acompanamiento'])) {
            $acompanamientoDao = new AcompaniamientoDAO();
            $acompanamiento = $acompanamientoDao->getById($_POST['acompanamiento']);
            $consultaInstance->setAcompanamiento($acompanamiento);
        }

        $consultaInstance->setArticulacionConInstituciones($_POST['articulacion']);
        $consultaInstance->setDiagnostico($_POST['diagnostico']);
        $consultaInstance->setObservaciones($_POST['observaciones']);

    }


    function destroy($id_consulta) {
        //$this->assertPermission();
        $consultaDao = new ConsultaDAO();
        $context['error'] = false;
        $context['msg'] = "Consulta eliminada correctamente";
        try {
            $consulta = $consultaDao->getById($id_consulta[1]);
            $consulta->setEliminado('1');
            $consultaDao->persist($consulta);
        } catch (Exception $e) {
            $context['error'] = true;
            $context['msg'] = "No se pudo eliminar la consulta: " . $e;
        }
        $context['paciente'] = $consulta->getPaciente();
        $param[0] = "";
        $param[1] = $consulta->getPaciente()->getId();

        return $this->index($param, $context);
    }

    public function getJsonForMap($param) {
        $id_paciente = $param[1];
        $institucionDao = new InstitucionDAO();
        $consultaDao = new ConsultaDAO();

        $pacienteDao = new PacienteDAO();
        $paciente = $pacienteDao->getById($id_paciente);
        $consultasPorPaciente = $consultaDao->getConsultasByPaciente($paciente);


        $data = [];

        foreach ($consultasPorPaciente as $consulta) {
            $new = [
                'institucion' => $consulta->getDerivacion()->getNombre(),
                'coordenada' => $consulta->getDerivacion()->getCoordenadas(),
            ];
            $data[] = $new;
        }
        return $this->jsonResponse($data);


    }
}

