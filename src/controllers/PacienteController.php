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

    function index(){
        
        $this->assertPermission();
        /*$pacientes = $this->getModel('Paciente')->findAll();
        $context['pacientes'] = $pacientes;*/
         $context['pacientes'] = [];
        return $this->twig_render("modules/pacientes/index.html", $context);
    }

    static function read($id_paciente){
        $instance = new PacienteController;
        $paciente=$instance->getModel('Paciente')->findOneBy(array('id' => $id_paciente[1]));
        $context['paciente'] = $paciente;
        return $instance->twig_render("modules/pacientes/ver.html", $context);
    }
    function searchView(){
        
        
        $tipos_doc = $this->getModel('TipoDocumento')->findAll();
        $parameters=array(
          'tipos_dnis' => $tipos_doc
        );
        return $this->twig_render("modules/pacientes/buscar.html", $parameters);
    }

    function search(){
        $query="select p from Paciente p where (p.nombre = '".$_POST['nombre']."' OR p.apellido = '".$_POST['apellido']."' OR p.tipoDoc= '".$_POST['tipo_doc']."' AND p.numero= '".$_POST['numero']."' OR p.nroHistoriaClinica= '".$_POST['nro_historia_clinica']."')";
        $q = $this->entityManager()->createQuery($query);
        $pacientes = $q->getResult();
        $context['pacientes'] = $pacientes;
        return $this->twig_render("modules/pacientes/index.html", $context);
    }

    function new(){
        
        $this->assertPermission();
        $obras_sociales = $this->getModel('ObraSocial')->findAll();
        $tipos_doc = $this->getModel('TipoDocumento')->findAll();
        $regiones_sanitarias = $this->getModel('RegionSanitaria')->findAll();
        $partidos = $this->getModel('Partido')->findAll();
        $generos = $this->getModel('Genero')->findAll();
        $parameters=array(
          'obras_sociales' => $obras_sociales,
          'tipos_dnis' => $tipos_doc,
          'regiones_sanitarias' => $regiones_sanitarias,
          'partidos' => $partidos,
          'generos' => $generos
        );


        return $this->twig_render("modules/pacientes/crear.html", $parameters);

    }
    function newNN(){
        
        return $this->twig_render("modules/pacientes/crear-nn.html", []);

    }
    private function setPaciente ($paciente){

        $paciente->setApellido($_POST['apellido']);
        $paciente->setNombre($_POST['nombre']);
        //Conversion a tipo Date, exigencia de doctrine para insertar
        $dateConversion = new DateTime($_POST['fecha_nac']);
        $paciente->setFechaNac($dateConversion);
        $paciente->setLugarNac($_POST['lugar_nac']);
        $localidad = $this->getModel('Localidad')->findOneBy(array('id'=>$_POST['localidad']));
        $paciente->setLocalidad($localidad);
        $region_sanitaria = $this->getModel('RegionSanitaria')->findOneBy(array('nombre'=>$_POST['region_sanitaria']));
        $paciente->setRegionSanitaria($region_sanitaria);
        $paciente->setDomicilio($_POST['domicilio']);
        $genero = $this->getModel('Genero')->findOneBy(array('id'=>$_POST['genero']));
        $paciente->setGenero($genero);

        if (is_null($_POST['tiene_documento'])){
          $paciente->setTieneDocumento('0');  
        } 
        else{
                $paciente->setTieneDocumento('1');  
            }
        $tipo_doc = $this->getModel('TipoDocumento')->findOneBy(array('id'=>$_POST['tipo_doc']));
        $paciente->setTipoDoc($tipo_doc);
        $paciente->setNumero($_POST['numero']);
        $paciente->setTel($_POST['tel']);
        $paciente->setNroHistoriaClinica($_POST['nro_historia_clinica']);
        $paciente->setNroCarpeta($_POST['nro_carpeta']);
        $obra_social = $this->getModel('ObraSocial')->findOneBy(array('id'=>$_POST['obra_social']));
        $paciente->setObraSocial($obra_social);
        return $paciente;  
    }
    private function notNulls(){
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
    function create(){
        
        if ($this->validateParams($this->notNulls())){
            $paciente = new Paciente();
            $this->entityManager()->persist($this->setPaciente($paciente));
            $this->entityManager()->flush();
            header('Location: /modulo/pacientes');
         } else{
            echo "No se pudo ingresar el paciente, faltaron completar algunos campos obligatorios.";
         }
    }
    function createNN(){
        $paciente = new Paciente();
        $paciente->setApellido('NN');
        $paciente->setNombre('NN');
        $paciente->setNroHistoriaClinica($_POST['nro_historia_clinica']);
        $this->entityManager()->persist($paciente);
        $this->entityManager()->flush();
        header('Location: /modulo/pacientes');
    }

    static function updateView($id_paciente){
        $instance = new PacienteController();
        $paciente = $instance->getModel('Paciente')->findOneBy(array('id' => $id_paciente[1]));
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
          'generos' => $generos,
          'paciente' => $paciente
        );
        return $instance->twig_render("modules/pacientes/modificar.html", $parameters);
    }

    static function update($id_paciente){
        $instance = new PacienteController();
        if ($instance->validateParams($instance->notNulls())){
            $paciente=$instance->getModel('Paciente')->findOneBy(array('id' => $id_paciente));
            $instance->entityManager()->merge($instance->setPaciente($paciente));
            $instance->entityManager()->flush();
            header('Location: /modulo/pacientes');
         } else{
            echo "No se pudo modificar el paciente, faltaron completar algunos campos obligatorios.";
         }
    }

    static function delete($id_paciente){
        $instance = new PacienteController();
        $paciente = $instance->getModel('Paciente')->findOneBy(array('id'=>$id_paciente[1]));
        $instance->entityManager()->remove($paciente);
        $instance->entityManager()->flush();
        header('Location: /modulo/pacientes');
    }
}

