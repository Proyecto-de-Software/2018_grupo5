<?php
/**
 * Created by PhpStorm.
 * User: seba
 * Date: 11/11/18
 * Time: 01:18
 */

require_once(CODE_ROOT . "/controllers/Controller.php");
use controllers\Controller;

class ReportesController extends Controller {

    function index(){
        echo $this->twig_render('modules/reportes/index.html',[]);
    }

    function getJsonByReason(){
        //datos mockeados
        $data = ['Receta Médica'=> 5, 'Control por Guardia'=>4, 'Consulta'=>3, 'Intento de Suicidio'=>2, 'Interconsulta'=>0, 'Otras'=> 3];
        return $this->jsonResponse($data);
    }

    function getJsonByGender(){
        //datos mockeados
        $data = ['Masculino'=> 5, 'Femenino'=>4, 'Otro'=>3];
        return $this->jsonResponse($data);
    }
    function getJsonByLocation(){
        //datos mockeados
        $data = ['De La costa'=> 7, 'La plata'=>8, 'Ensenada'=>4, 'Berisso'=>10];
        return $this->jsonResponse($data);
    }
}