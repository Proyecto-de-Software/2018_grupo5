<?php


require_once(CODE_ROOT . "/controllers/Controller.php");
require_once (CODE_ROOT . "/models/Paciente.php");
require_once (CODE_ROOT . "/models/Genero.php");
require_once (CODE_ROOT . "/models/RegionSanitaria.php");
require_once (CODE_ROOT . "/models/TipoDocumento.php");
require_once (CODE_ROOT . "/models/Localidad.php");
require_once (CODE_ROOT . "/models/Partido.php");
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
        $obras_sociales = $instance->getModel('ObraSocial')->findAll();
        $tipos_doc = $instance->getModel('TipoDocumento')->findAll();
        $regiones_sanitarias = $instance->getModel('RegionSanitaria')->findAll();
        $partidos = $instance->getModel('Partido')->findAll();
        $generos = $instance->getModel('Genero')->findAll();
        $parameters=array(
          'obras_sociales' => $obras_sociales,
          'tipos_dnis' => $tipos_doc,
          'regiones_sanitarias' => $regiones_sanitarias,
          'partidos' => $partidos,
          'generos' => $generos
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

            if (is_null($_POST['tiene_documento'])){
              $paciente->setTieneDocumento('0');  
            } 
            else{
                    $paciente->setTieneDocumento('1');  
                }
            $tipo_doc = $instance->getModel('TipoDocumento')->findOneBy(array('id'=>$_POST['tipo_doc']));
            $paciente->setTipoDoc($tipo_doc);
            $paciente->setNumero($_POST['numero']);
            $paciente->setTel($_POST['tel']);
            $paciente->setNroHistoriaClinica($_POST['nro_historia_clinica']);
            $paciente->setNroCarpeta($_POST['nro_carpeta']);
            $obra_social = $instance->getModel('ObraSocial')->findOneBy(array('id'=>$_POST['obra_social']));
            $paciente->setObraSocial($obra_social);  
            $instance->entityManager()->persist($paciente);
            $instance->entityManager()->flush();
            header('Location: /modulo/pacientes');


    }
    static function delete($nro_documento){
        $instance = new PacienteController();
        $paciente = $instance->getModel('Paciente')->findOneBy(array('numero'=>$nro_documento[1]));
        $instance->entityManager()->remove($paciente);
        $instance->entityManager()->flush();
        header('Location: /modulo/pacientes');
    }
}

