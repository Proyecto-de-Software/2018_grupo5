<?php


require_once(CODE_ROOT . "/controllers/Controller.php");
require_once (CODE_ROOT . "/models/Paciente.php");
require_once (CODE_ROOT . "/models/Genero.php");
require_once (CODE_ROOT . "/models/RegionSanitaria.php");
require_once (CODE_ROOT . "/models/TipoDocumento.php");
require_once (CODE_ROOT . "/models/Localidad.php");
require_once (CODE_ROOT . "/models/ObraSocial.php");
use controllers\Controller;

class PacienteController extends Controller {

    static function index(){
        $instance = new PacienteController();
        $pacientes = $instance->getModel('Paciente')->findAll();
        $context['pacientes'] = $pacientes;
        return $instance->twig_render("modules/pacientes/index.html", $context);
    }
    static function new(){
        $instance = new PacienteController();
   


        $o_sociales=array();
        $obras_sociales = $instance->getModel('ObraSocial')->findAll();
        foreach ($obras_sociales as $key => $value) {
          array_push($o_sociales, $value->getNombre());
        }
        $tipo_dnis=array();
        $tipos_doc = $instance->getModel('TipoDocumento')->findAll();
        foreach ($tipos_doc as $key => $value) {
          array_push($tipo_dnis, $value->getNombre());
        }

        $parameters=array(
          'obras_sociales' => $o_sociales,
          'tipos_dnis' => $tipo_dnis
        );


        return $instance->twig_render("modules/pacientes/crear.html", $parameters);

    }

    static function create(){
            
            $instance = new PacienteController();
            $paciente = new Paciente();
            $paciente->setApellido($_POST['apellido']);
            $paciente->setNombre($_POST['nombre']);
            //Conversion a tipo Date, exigencia de doctrine para insertar
            $dateConversion = new DateTime($_POST['fecha_nac']);
            $paciente->setFechaNac($dateConversion);
            $paciente->setLugarNac($_POST['lugar_nac']);
            $localidad = $instance->getModel('Localidad')->findOneBy(array('nombre'=>$_POST['localidad']));
            $paciente->setLocalidad($localidad);
            $region_sanitaria = $instance->getModel('RegionSanitaria')->findOneBy(array('nombre'=>$_POST['region_sanitaria']));
            $paciente->setRegionSanitaria($region_sanitaria);
            $paciente->setDomicilio($_POST['domicilio']);
            $genero = $instance->getModel('Genero')->findOneBy(array('nombre'=>$_POST['genero']));
            $paciente->setGenero($genero);
            $paciente->setTieneDocumento($_POST['tiene_documento']);
            $tipo_doc = $instance->getModel('TipoDocumento')->findOneBy(array('nombre'=>$_POST['tipo_doc']));
            $paciente->setTipoDoc($tipo_doc);
            $paciente->setNumero($_POST['numero']);
            $paciente->setTel($_POST['tel']);
            $paciente->setNroHistoriaClinica($_POST['nro_historia_clinica']);
            $paciente->setNroCarpeta($_POST['nro_carpeta']);
            $obra_social = $instance->getModel('ObraSocial')->findOneBy(array('nombre'=>$_POST['obra_social']));
            $paciente->setObraSocial($obra_social);  
            $instance->entityManager()->persist($paciente);
            $instance->entityManager()->flush();


    }
}
