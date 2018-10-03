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
        /*$pacientes = $this->getModel('Paciente')->findAll();
        $context['pacientes'] = $pacientes;*/
        $context['pacientes'] = [];
        return $this->twig_render("modules/pacientes/index.html", $context);
    }

    static function read($id_paciente) {
        $instance = new PacienteController;
        $paciente = $instance->getModel('Paciente')->findOneBy(['id' => $id_paciente[1]]);
        $context['paciente'] = $paciente;
        return $instance->twig_render("modules/pacientes/ver.html", $context);
    }

    function searchView() {


        $tipos_doc = $this->getModel('TipoDocumento')->findAll();
        $parameters = [
            'tipos_dnis' => $tipos_doc,
        ];
        return $this->twig_render("modules/pacientes/buscar.html", $parameters);
    }

    function search() {
        if($_POST['nro_historia_clinica'] == 0) $_POST['nro_historia_clinica'] = -1;
        if($_POST['numero'] == 0) $_POST['numero'] = -1;
        $qb = $this->entityManager()->createQueryBuilder();
        $qb->select('p')
            ->from('Paciente', 'p')
            ->where($qb->expr()->orX(
                $qb->expr()->eq('p.nombre', '?1'),
                $qb->expr()->eq('p.apellido', '?2'),
                $qb->expr()->andX(
                    $qb->expr()->eq('p.tipoDoc', '?3'),
                    $qb->expr()->eq('p.numero', '?4')),
                $qb->expr()->eq('p.nroHistoriaClinica', '?5')

            ));
        $qb->setParameters([1 => $_POST['nombre'], 2 => $_POST['apellido'], 3 => $_POST['tipo_doc'], 4 => $_POST['numero'], 5 => $_POST['nro_historia_clinica']]);
        $query = $qb->getQuery();
        $context = ['pacientes' => $query->getResult(),
            'realiceBusqueda' => true,

        ];
        return $this->twig_render("modules/pacientes/index.html", $context);
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

        return $this->twig_render("modules/pacientes/crear.html", $parameters);

    }

    function newNNView() {
        $this->assertPermission();
        return $this->twig_render("modules/pacientes/crear-nn.html", []);
    }

    private function setPaciente($paciente) {

        $paciente->setApellido($_POST['apellido']);
        $paciente->setNombre($_POST['nombre']);
        //Conversion a tipo Date, exigencia de doctrine para insertar
        $dateConversion = new DateTime($_POST['fecha_nac']);
        $paciente->setFechaNac($dateConversion);
        $paciente->setLugarNac($_POST['lugar_nac']);

        if(isset($_POST['localidad'])) {
            $localidad = $this->getModel('Localidad')->findOneBy(['id' => $_POST['localidad']]);
            $paciente->setLocalidad($localidad);
        }
        $paciente->setDomicilio($_POST['domicilio']);
        $genero = $this->getModel('Genero')->findOneBy(['id' => $_POST['genero']]);
        $paciente->setGenero($genero);
        //Los checkbox vienen sin setear cuando no son tildados en los formularios, por eso tenemos que hacer este chequeo..
        isset($_POST['tiene_documento']) ? $paciente->setTieneDocumento('1') : $paciente->setTieneDocumento('0');
        $tipo_doc = $this->getModel('TipoDocumento')->findOneBy(['id' => $_POST['tipo_doc']]);
        $paciente->setTipoDoc($tipo_doc);
        $paciente->setNumero($_POST['numero']);
        $paciente->setTel($_POST['tel']);
        $paciente->setNroHistoriaClinica($_POST['nro_historia_clinica']);
        $paciente->setNroCarpeta($_POST['nro_carpeta']);
        $obra_social = $this->getModel('ObraSocial')->findOneBy(['id' => $_POST['obra_social']]);
        $paciente->setObraSocial($obra_social);
        return $paciente;
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

        if($this->validateParams($this->notNulls())) {

            if($this->existeHistoriaClinica()) {
                $context = ['existeHistoriaClinica' => true,
                    'pacientes' => [],
                ];
                return $this->twig_render("modules/pacientes/index.html", $context);
            }


            $paciente = new Paciente();
            $this->entityManager()->persist($this->setPaciente($paciente));
            $this->entityManager()->flush();
            $context = ['crud_action' => true,
                'action' => 'agregado',
                'pacientes' => [],

            ];
            return $this->twig_render("modules/pacientes/index.html", $context);
        } else {
            echo "No se pudo ingresar el paciente, faltaron completar algunos campos obligatorios.";
        }
    }

    function createNN() {
        $nro_hist_clinica = $_POST['nro_historia_clinica'];


        if($nro_hist_clinica <> "") {
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
            echo "No se pudo dar de alta al paciente como NN, debe asignar un Nº de historia clínica obligatoriamente.";
        }
    }

    static function updateView($id_paciente) {
        $instance = new PacienteController();
        $paciente = $instance->getModel('Paciente')->findOneBy(['id' => $id_paciente[1]]);
        $obras_sociales = $instance->getModel('ObraSocial')->findAll();
        $tipos_doc = $instance->getModel('TipoDocumento')->findAll();
        $regiones_sanitarias = $instance->getModel('RegionSanitaria')->findAll();
        $partidos = $instance->getModel('Partido')->findAll();
        $generos = $instance->getModel('Genero')->findAll();
        $parameters = [
            'obras_sociales' => $obras_sociales,
            'tipos_dnis' => $tipos_doc,
            'regiones_sanitarias' => $regiones_sanitarias,
            'partidos' => $partidos,
            'generos' => $generos,
            'paciente' => $paciente,
        ];
        return $instance->twig_render("modules/pacientes/modificar.html", $parameters);
    }

    static function update($id_paciente) {
        $instance = new PacienteController();
        if($instance->validateParams($instance->notNulls())) {

            if($instance->existeHistoriaClinicaModificar()) {
                $context = ['existeHistoriaClinica' => true,
                    'pacientes' => [],
                ];
                return $instance->twig_render("modules/pacientes/index.html", $context);
            }


            $paciente = $instance->getModel('Paciente')->findOneBy(['id' => $id_paciente]);
            $instance->entityManager()->merge($instance->setPaciente($paciente));
            $instance->entityManager()->flush();
            $context = ['crud_action' => true,
                'action' => 'modificado',
                'pacientes' => [],
            ];
            return $instance->twig_render("modules/pacientes/index.html", $context);
        } else {
            echo "No se pudo modificar el paciente, faltaron completar algunos campos obligatorios.";
        }
    }

    static function delete($id_paciente) {
        $instance = new PacienteController();
        $paciente = $instance->getModel('Paciente')->findOneBy(['id' => $id_paciente[1]]);
        $instance->entityManager()->remove($paciente);
        $instance->entityManager()->flush();
        $context = ['crud_action' => true,
            'action' => 'eliminado',
            'pacientes' => [],
        ];
        return $instance->twig_render("modules/pacientes/index.html", $context);
    }

    private function existeHistoriaClinica() {
        if(isset($_POST['nro_historia_clinica'])) {
            $encontre = $this->getModel('Paciente')->findOneBy(['nroHistoriaClinica' => $_POST['nro_historia_clinica']]);
            if(!is_null($encontre)) {
                return true;
            }
            return false;
        }
    }

    private function existeHistoriaClinicaModificar() {
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
            return false;
        }
    }

}

