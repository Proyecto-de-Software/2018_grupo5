<?php


require_once(CODE_ROOT . "/controllers/Controller.php");
require_once(CODE_ROOT . "/models/Paciente.php");
require_once(CODE_ROOT . "/models/Genero.php");
require_once(CODE_ROOT . "/models/RegionSanitaria.php");
require_once(CODE_ROOT . "/models/TipoDocumento.php");
require_once(CODE_ROOT . "/models/Localidad.php");
require_once(CODE_ROOT . "/models/Partido.php");
require_once(CODE_ROOT . "/models/ObraSocial.php");

use controllers\Controller;

class PacienteController extends Controller {

    function index() {
        $this->assertPermission();
        $context['pacientes'] = [];
        return $this->twig_render("modules/pacientes/index.html", $context);
    }

    function readView($id_paciente) {
        $this->assertPermission();
        $paciente = $this->getModel('Paciente')->findOneBy(['id' => $id_paciente[1]]);
        if($paciente == null || $paciente->getEliminado() == '1') $paciente = null;
        $context['paciente'] = $paciente;
        return $this->twig_render("modules/pacientes/ver.html", $context);
    }

    function searchView() {
        $this->assertPermission();
        $tipos_doc = $this->getModel('TipoDocumento')->findAll();
        $parameters = [
            'tipos_dnis' => $tipos_doc,
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

        $result = $this->searchPacientes(
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

    private function searchPacientes($nombre, $apellido, $tipo_doc, $doc_numero, $numeroHistorioClinica, $deleted) {

        $qb = $this->entityManager()->createQueryBuilder();
        $qb->select('p')
            ->from('Paciente', 'p')
            ->where($qb->expr()->orX(
                $qb->expr()->like('p.nombre', '?1'),
                $qb->expr()->like('p.apellido', '?2'),
                $qb->expr()->andX(
                    $qb->expr()->eq('p.tipoDoc', '?3'),
                    $qb->expr()->eq('p.numero', '?4')
                ),
                $qb->expr()->eq('p.nroHistoriaClinica', '?5')
            ),
                $qb->expr()->andX(
                    $qb->expr()->eq('p.eliminado', '?6')
                )
            );


        $parameters =
            [
                1 => '',
                2 => '',
                3 => $tipo_doc,
                4 => $doc_numero,
                5 => $numeroHistorioClinica,
                6 => $deleted,
            ];

        $parameters[1] = ($nombre !== "") ? "%" . $nombre . "%" : $nombre;
        $parameters[2] = ($apellido !== "") ? "%" . $apellido . "%" : $apellido;

        $qb->setParameters($parameters);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    function newView() {
        $this->assertPermission();

        $obras_sociales = $this->getModel('ObraSocial')->findAll();
        $tipos_doc = $this->getModel('TipoDocumento')->findAll();
        $regiones_sanitarias = $this->getModel('RegionSanitaria')->findAll();
        $partidos = $this->getModel('Partido')->findAll();
        $generos = $this->getModel('Genero')->findAll();
        $parameters = [
            'obras_sociales' => $obras_sociales,
            'tipos_dnis' => $tipos_doc,
            'regiones_sanitarias' => $regiones_sanitarias,
            'partidos' => $partidos,
            'generos' => $generos,
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
            $localidad = $this->getModel('Localidad')->findOneBy(['id' => $_POST['localidad']]);
            $pacienteInstance->setLocalidad($localidad);
        }
        $pacienteInstance->setDomicilio($_POST['domicilio']);
        $genero = $this->getModel('Genero')->findOneBy(['id' => $_POST['genero']]);
        $pacienteInstance->setGenero($genero);
        //Los checkbox vienen sin setear cuando no son tildados en los formularios, por eso tenemos que hacer este chequeo..
        isset($_POST['tiene_documento']) ? $pacienteInstance->setTieneDocumento('1') : $pacienteInstance->setTieneDocumento('0');
        $tipo_doc = $this->getModel('TipoDocumento')->findOneBy(['id' => $_POST['tipo_doc']]);
        $pacienteInstance->setTipoDoc($tipo_doc);
        $pacienteInstance->setNumero($_POST['numero']);
        $pacienteInstance->setTel($_POST['tel']);
        $pacienteInstance->setNroHistoriaClinica($_POST['nro_historia_clinica']);
        $pacienteInstance->setNroCarpeta($_POST['nro_carpeta']);
        $obra_social = $this->getModel('ObraSocial')->findOneBy(['id' => $_POST['obra_social']]);
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
                $this->entityManager()->persist($this->setPaciente($paciente));
                $this->entityManager()->flush();
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

        $nro_hist_clinica = $_POST['nro_historia_clinica'];


        if($nro_hist_clinica <> "" && $nro_hist_clinica <> "0") {
            if($this->existeHistoriaClinica()) {
                $context = ['existeHistoriaClinica' => true,
                    'pacientes' => [],
                ];
                return $this->twig_render("modules/pacientes/index.html", $context);
            }
            $paciente = new Paciente();
            $paciente->setApellido('NN');
            $paciente->setNombre('NN');
            $paciente->setNroHistoriaClinica($_POST['nro_historia_clinica']);
            $this->entityManager()->persist($paciente);
            $this->entityManager()->flush();
            $context = ['crud_action' => true,
                'action' => 'agregado',
                'pacientes' => [],
            ];
            return $this->twig_render("modules/pacientes/index.html", $context);
        } else {
            $msg = "No se pudo dar de alta al paciente NN, debe asignar un Nº de historia clínica obligatoriamente. Tenga en cuenta que además no puede ser cero.";
            $context = ['msg' => $msg];
            return $this->twig_render("modules/pacientes/crear-nn.html", $context);
        }
    }

    function updateView($id_paciente) {
        $this->assertPermission();
        $paciente = $this->getModel('Paciente')->findOneBy(['id' => $id_paciente[1]]);
        $obras_sociales = $this->getModel('ObraSocial')->findAll();
        $tipos_doc = $this->getModel('TipoDocumento')->findAll();
        $regiones_sanitarias = $this->getModel('RegionSanitaria')->findAll();
        $partidos = $this->getModel('Partido')->findAll();
        $generos = $this->getModel('Genero')->findAll();
        $parameters = [
            'obras_sociales' => $obras_sociales,
            'tipos_dnis' => $tipos_doc,
            'regiones_sanitarias' => $regiones_sanitarias,
            'partidos' => $partidos,
            'generos' => $generos,
            'paciente' => $paciente,
        ];
        return $this->twig_render("modules/pacientes/formPaciente.html", $parameters);
    }


    static function update($id_paciente) {
        $instance = new PacienteController();
        $instance->assertPermission();
        $context = [
            'error' => false,
            'fechaIncorrecta' => false,
            'existeHistoriaClinica' => false,
            'pacientes' => [],
            'id_modificado' => $id_paciente,
        ];

        if(!$instance->validateParams($instance->notNulls())) {
            $context['error'] = true;
            $context['msg'] = 'No se pudo modificar el paciente, faltaron completar algunos campos obligatorios.';
            return $instance->twig_render("modules/pacientes/index.html", $context);
        }

        if(!$instance->validarFecha($_POST['fecha_nac'])) {
            $context['fechaIncorrecta'] = true;
            return $instance->twig_render("modules/pacientes/index.html", $context);
        }

        $nro_hist_cli = $_POST['nro_historia_clinica'];

        if(($nro_hist_cli !== "") && ($instance->existeHistoriaClinicaModificar())) {
            $context['existeHistoriaClinica'] = true;
            return $instance->twig_render("modules/pacientes/index.html", $context);
        }

        $paciente = $instance->getModel('Paciente')->findOneBy(['id' => $id_paciente]);

        try {
            $instance->entityManager()->merge($instance->setPaciente($paciente));
            $instance->entityManager()->flush();
            $context['crud_action'] = true;
            $context['action'] = 'modificado';
            return $instance->twig_render("modules/pacientes/index.html", $context);

        } catch (Exception $e) {
            $context['error'] = true;
            $context['msg'] = $e->getMessage();
            return $instance->twig_render("modules/pacientes/index.html", $context);
        }
    }


    static function delete($id_paciente) {
        $instance = new PacienteController();
        $instance->assertPermission();

        $paciente = $instance->getModel('Paciente')->findOneBy(['id' => $id_paciente[1]]);
        $paciente->setEliminado('1');
        $instance->entityManager()->merge($paciente);
        $instance->entityManager()->flush();
        $context = ['crud_action' => true,
            'action' => 'eliminado',
            'pacientes' => [],
        ];
        return $instance->twig_render("modules/pacientes/index.html", $context);
    }

    private function existeHistoriaClinica() {
        $nroHistClinica = $_POST['nro_historia_clinica'];
        if($nroHistClinica == '0') {
            return false;
        }
        if(isset($nroHistClinica)) {
            $encontre = $this->getModel('Paciente')->findOneBy(['nroHistoriaClinica' => $nroHistClinica]);
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
            $qb = $this->entityManager()->createQueryBuilder();
            $qb->select('p')
                ->from('Paciente', 'p')
                ->where($qb->expr()->AndX(
                    $qb->expr()->eq('p.nroHistoriaClinica', '?1'),
                    $qb->expr()->neq('p.id', '?2')

                ));
            $qb->setParameters([1 => $_POST['nro_historia_clinica'], 2 => $_POST['id_paciente']]);
            $query = $qb->getQuery();
            $encontre = $query->getResult();
            if(sizeof($encontre) > 0) {
                return true;
            }
        }
        return false;
    }

}

