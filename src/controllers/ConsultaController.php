<?php
//
require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class ConsultaController extends Controller {

    private $consultaDao;
    private $pacienteDao;

    function __construct() {
        parent::__construct();
        $this->consultaDao = new ConsultaDAO();
        $this->pacienteDao = new PacienteDAO();
    }

    public function index($param, $context = null) {
        $this->assertPermission();
        /**@doc: Show all consultations of one patient */

        $paciente = $this->pacienteDao->getById($param[1]);
        $context['paciente'] = $paciente;
        $context['consultas'] = $this->consultaDao->getConsultasByPaciente($paciente);
        return $this->twig_render("modules/consultas/index.html", $context);
    }

    public function view($param) {
        $this->assertPermission();
        /**@doc: view data of one consultation */

        $context['consulta'] = $this->consultaDao->getById($param['id']);
        return $this->twig_render("modules/consultas/view.html", $context);
    }

    public function updateView($param) {
        /**@doc: view for update one Consulta */
        $this->assertPermission();

        return $this->renderCreateOrUpdateView($param['id']);
    }

    public function createView() {
        /**@doc: view for create a new consultation */
        $this->assertPermission();

        return $this->renderCreateOrUpdateView();
    }

    private function renderCreateOrUpdateView($consulta_id = null) {
        /**@doc: generic method for render the creation/update for consultation */

        $context = [
            'motivos' => (new MotivoConsultaDAO())->getAll(),
            'acompanamientos' => (new AcompaniamientoDAO())->getAll(),
            'tratamientos_farmacologicos' => (new TratamientoFarmacologicoDAO())->getAll(),
        ];

        if(isset($consulta_id)) {
            $context['consulta'] = $this->consultaDao->getById($consulta_id);
        }

        return $this->twig_render("modules/consultas/formConsulta.html", $context);
    }

    public function create() {
        $this->assertPermission();
        $consulta = new Consulta();
        return $this->createOrUpdate($consulta);
    }

    function update() {
        $this->assertPermission();
        $consulta = $this->consultaDao->getById($_POST['id']);
        return $this->createOrUpdate($consulta, true);
    }

    private function notNullsParameters() {
        return [
            "paciente_id",
            "fecha_consulta",
            "motivo",
            "diagnostico",
        ];
    }


    private function createOrUpdate(&$consultaInstance, $isUpdate = false) {

        $response = [
            'action' => $isUpdate ? 'update' : 'create',
            'error' => true,
            'code' => null,
            'msg' => null,
            'id' => null,
        ];

        if(!validateDate($_POST['fecha_consulta'])) {
            $response['code'] = 2;
            $response['msg'] = "La fecha de consulta ingresada no es correcta.";
            return $this->jsonResponse($response);
        }

        if($this->validateParams($this->notNullsParameters())) {
            try {
                $this->setConsultaFromRequest($consultaInstance);
                $this->consultaDao->persist($consultaInstance);
                $response['code'] = 0;
                $response['msg'] = "Consulta " . ($isUpdate ? 'modificada' : 'agregada');
                $response['id'] = $consultaInstance->getId();
                $response['error'] = false;
            } catch (Exception $e) {
                $response["msg"] = "Error" . $this->returnParamIfUserIsAdmin($e->getMessage());
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

        $paciente = $this->pacienteDao->getById($_POST['paciente_id']);
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
        $this->assertPermission();
        $context['error'] = false;
        try {
            $consulta = $this->consultaDao->getById($id_consulta[1]);
            $consulta->setEliminado('1');
            $this->consultaDao->persist($consulta);
            $this->redirect("/modulo/consultas/" . $consulta->getPaciente()->getId());
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
        $this->assertPermission();
        /**@doc: return all derivations for the paciente */
        $data = [];

        $paciente = $this->pacienteDao->getById($param[1]);
        $consultasPorPaciente = $this->consultaDao->getConsultasByPaciente($paciente);

        foreach ($consultasPorPaciente as $consulta) {
            if($consulta->getDerivacion() != null) {
                $new = [
                    'institucion' => $consulta->getDerivacion()->getNombre(),
                    'coordenada' => $consulta->getDerivacion()->getCoordenadas(),
                ];
                $data[] = $new;
            }
        }
        return $this->jsonResponse($data);
    }
}

