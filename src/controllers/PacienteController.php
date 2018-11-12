<?php
require_once(CODE_ROOT . "/controllers/Controller.php");


use controllers\Controller;

class PacienteController extends Controller {

    private $pacienteDao;

    function __construct() {
        parent::__construct();
        $this->pacienteDao = new PacienteDAO();
    }

    function pacientesJSON(){
        $data = [];
        $this->pacienteDao->getAllActive();
        $pacientes = $this->pacienteDao->getAllActive();
        foreach ($pacientes as $paciente) {
                $data[] = $paciente->jsonSerialize();
        }
        return $this->jsonResponse($data);
    }

    function index() {
        $this->assertPermission();
        $context['pacientes'] = [];
        return $this->twig_render("modules/pacientes/index.html", $context);
    }

    function readView($id_paciente) {
        $this->assertPermission();
        $context['paciente']  = $this->pacienteDao->getByIdIfIsActive($id_paciente[1]);
        return $this->twig_render("modules/pacientes/ver.html", $context);
    }

    function searchView() {
        $this->assertPermission();
        $documento_tipos_dao = new TipoDocumentoDao();
        $parameters = [
            'tipos_dnis' => $documento_tipos_dao->getAll(),
        ];
        return $this->twig_render("modules/pacientes/buscar.html", $parameters);
    }

    function search() {
        $this->assertPermission();

        if($_GET['nro_historia_clinica'] == 0) {
            $_GET['nro_historia_clinica'] = -1;
        }

        if($_GET['numero'] == 0) {
            $_POST['numero'] = -1;
        }

        $result = $this->pacienteDao->searchPacientes(
            $_GET['nombre'],
            $_GET['apellido'],
            $_GET['tipo_doc'],
            $_GET['numero'],
            $_GET['nro_historia_clinica'],
            0
        );

        $context = [
            'pacientes' => $result,
            'realiceBusqueda' => true,
        ];

        return $this->twig_render("modules/pacientes/index.html", $context);
    }

    private function validarFecha($unaFecha) {
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

    function newView() {
        $this->assertPermission();

        $parameters = [
            'obras_sociales' => (new ObraSocialDAO())->getAll(),
            'tipos_dnis' => (new TipoDocumentoDAO())->getAll(),
            'regiones_sanitarias' => (new RegionSanitariaDAO())->getAll(),
            'partidos' => (new PartidoDao())->getAll(),
            'generos' => (new GeneroDao())->getAll(),
        ];

        return $this->twig_render("modules/pacientes/formPaciente.html", $parameters);

    }

    function newNNView() {
        $this->assertPermission();
        return $this->twig_render("modules/pacientes/crear-nn.html", []);
    }

    private function setPaciente($pacienteInstance) {
        $pacienteInstance->setApellido($_POST['apellido']);
        $pacienteInstance->setNombre($_POST['nombre']);
        //Conversion a tipo Date, exigencia de doctrine para insertar
        $dateConversion = new DateTime($_POST['fecha_nac']);
        $pacienteInstance->setFechaNac($dateConversion);
        $pacienteInstance->setLugarNac($_POST['lugar_nac']);

        if(isset($_POST['localidad'])) {
            $localidadDao = new LocalidadDao();
            $localidad = $localidadDao->getById(['id' => $_POST['localidad']]);
            $pacienteInstance->setLocalidad($localidad);
        }
        $pacienteInstance->setDomicilio($_POST['domicilio']);

        $generoDao=new GeneroDao();
        $genero = $generoDao->getById(['id' => $_POST['genero']]);
        $pacienteInstance->setGenero($genero);
        //Los checkbox vienen sin setear cuando no son tildados en los formularios, por eso tenemos que hacer este chequeo..
        isset($_POST['tiene_documento']) ? $pacienteInstance->setTieneDocumento('1') : $pacienteInstance->setTieneDocumento('0');
        $tipoDocumentoDAO=new TipoDocumentoDAO();
        $tipo_doc = $tipoDocumentoDAO->getById(['id' => $_POST['tipo_doc']]);
        $pacienteInstance->setTipoDoc($tipo_doc);
        $pacienteInstance->setNumero($_POST['numero']);
        $pacienteInstance->setTel($_POST['tel']);
        $pacienteInstance->setNroHistoriaClinica($_POST['nro_historia_clinica']);
        $pacienteInstance->setNroCarpeta($_POST['nro_carpeta']);
        $ObraSocialDao = new ObraSocialDAO();
        $obra_social = $ObraSocialDao->getById(['id' => $_POST['obra_social']]);
        $pacienteInstance->setObraSocial($obra_social);
        return $pacienteInstance;
    }

    private function notNulls() {
        //Cargo los campos que no pueden ser nulos a un array para validar despues
        $notNulls = [
            "apellido",
            "nombre",
            "fecha_nac",
            "domicilio",
            "genero",
            "numero",
            "tipo_doc",
        ];
        return $notNulls;
    }

    function create() {
        /**
         * codigos
         * 0 = agregado correctamente
         * 1 = historia existente
         * 2 = faltan parametros
         * */
        $this->assertPermission();
        if(!$this->validarFecha($_POST['fecha_nac'])) {
            $response['error'] = true;
            $response['code'] = 2;
            $response['msg'] = "La fecha ingresada no es correcta.";
            return $this->jsonResponse($response);
        }
        $response = [
            'error' => true,
            'msg' => null,
        ];

        $nro_hist_cli = $_POST['nro_historia_clinica'];
        if($this->validateParams($this->notNulls())) {

            if(($nro_hist_cli !== "") && ($this->existeHistoriaClinica())) {
                $response['code'] = 1;
                $response['msg'] = "La historia clinica existe";
                return $this->jsonResponse($response);
            }

            $paciente = new Paciente();
            try {
                $this->pacienteDao->persist($this->setPaciente($paciente));
                $response['code'] = 0;
                $response['msg'] = "Paciente agregado";
                $response['id'] = $paciente->getId();
                $response['error'] = false;
            } catch (Exception $e) {

                $response["msg"] = "Error" . $e->getMessage();
                $response['code'] = 2;
                $response['error'] = true;

            }

            return $this->jsonResponse($response);

        } else {
            $response['code'] = 2;
            $response['msg'] = "No se pudo ingresar el paciente, faltaron completar algunos campos obligatorios.";
            return $this->jsonResponse($response);
        }
    }

    function createNN() {
        $this->assertPermission();
        $context = [
            'msg' => null,
        ];

        $nro_hist_clinica = $_POST['nro_historia_clinica'];

        if($nro_hist_clinica == "" || $nro_hist_clinica == "0") {
            $context['msg'] = "
            No se pudo dar de alta al paciente NN, 
            debe asignar un Nº de historia clínica obligatoriamente. 
            Tenga en cuenta que además no puede ser 0.";
            return $this->twig_render("modules/pacientes/crear-nn.html", $context);
        }

        if($this->existeHistoriaClinica()) {
            $context['msg'] = 'La historia clinica ya existe en el sistema!';
            return $this->twig_render("modules/pacientes/crear-nn.html", $context);
        }


        $paciente = new Paciente();
        $paciente->setApellido('NN');
        $paciente->setNombre('NN');
        $paciente->setNroHistoriaClinica($_POST['nro_historia_clinica']);
        $this->pacienteDao->persist($paciente);

        $context = [
            'crud_action' => true,
            'action' => 'agregado',
            'pacientes' => [],
            'nn_historiaClinica' => $paciente->getNroHistoriaClinica(),
        ];

        return $this->twig_render("modules/pacientes/index.html", $context);
    }

    function updateView($id_paciente) {
        $this->assertPermission();
        $paciente = $this->pacienteDao->getById($id_paciente[1]);
        $parameters = [
            'obras_sociales' => (new ObraSocialDAO())->getAll(),
            'tipos_dnis' => (new TipoDocumentoDAO())->getAll(),
            'regiones_sanitarias' => (new RegionSanitariaDAO())->getAll(),
            'partidos' => (new PartidoDao())->getAll(),
            'generos' => (new GeneroDao())->getAll(),
            'paciente' => $paciente,
        ];
        return $this->twig_render("modules/pacientes/formPaciente.html", $parameters);
    }

    function update($id_paciente) {

        $this->assertPermission();
        $context = [
            'error' => false,
            'fechaIncorrecta' => false,
            'existeHistoriaClinica' => false,
            'pacientes' => [],
            'id_modificado' => $id_paciente,
        ];

        if(!$this->validateParams($this->notNulls())) {
            $context['error'] = true;
            $context['msg'] = 'No se pudo modificar el paciente, faltaron completar algunos campos obligatorios.';
            return $this->twig_render("modules/pacientes/index.html", $context);
        }

        if(!$this->validarFecha($_POST['fecha_nac'])) {
            $context['fechaIncorrecta'] = true;
            return $this->twig_render("modules/pacientes/index.html", $context);
        }

        $nro_hist_cli = $_POST['nro_historia_clinica'];

        if(($nro_hist_cli !== "") && ($this->existeHistoriaClinicaModificar())) {
            $context['existeHistoriaClinica'] = true;
            return $this->twig_render("modules/pacientes/index.html", $context);
        }

        $paciente = $this->pacienteDao->getById($id_paciente);
        try {
            if ($paciente == null) {
                throw new Exception("Paciente no encontrado.");
            }
            $p = $this->setPaciente($paciente);
            $this->pacienteDao->update($p);
            $context['crud_action'] = true;
            $context['action'] = 'modificado';
            return $this->twig_render("modules/pacientes/index.html", $context);

        } catch (Exception $e) {
            $context['error'] = true;
            $context['msg'] = $e->getMessage();
            return $this->twig_render("modules/pacientes/index.html", $context);
        }
    }

    function delete($id_paciente) {
        $this->assertPermission();
        $paciente = $this->pacienteDao->getById($id_paciente[1]);
        $paciente->setEliminado('1');
        $this->pacienteDao->update($paciente);
        $context = ['crud_action' => true,
            'action' => 'eliminado',
            'pacientes' => [],
        ];
        return $this->twig_render("modules/pacientes/index.html", $context);
    }

    private function existeHistoriaClinica() {
        $nroHistClinica = $_POST['nro_historia_clinica'];
        if($nroHistClinica == '0') {
            return false;
        }
        if(isset($nroHistClinica)) {
            $encontre = $this->pacienteDao->getByNumberOfClinicHistory($nroHistClinica);
            if(!is_null($encontre)) {
                return true;
            }
        }
        return false;
    }

    private function existeHistoriaClinicaModificar() {
        if($_POST['nro_historia_clinica'] == '0') {
            return false;
        }

        if(isset($_POST['nro_historia_clinica'])) {
            return $this->pacienteDao->isInUseClinicHistoryNumberForPatienceId($_POST['nro_historia_clinica'], $_POST['id_paciente']);
        }
        return false;
    }

}
