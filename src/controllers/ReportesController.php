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

    private $consultaDao;
    function __construct() {
        parent::__construct();
        $this->consultaDao = new ConsultaDAO();
    }

    function index(){
        echo $this->twig_render('modules/reportes/index.html',[]);
    }

    function getJsonByReason(){
        $result = $this->consultaDao->getGroupedByReason();
        $data = $this->returnAsArrayTuples($result);
        return $this->jsonResponse($data);
    }

    function getJsonByGender(){
        $result = $this->consultaDao->getGroupedByGender();
        $data = $this->returnAsArrayTuples($result);
        return $this->jsonResponse($data);
    }
    function getJsonByLocation(){
        $result = $this->consultaDao->getGroupedByLocation();
        $data = $this->returnAsArrayTuples($result);
        return $this->jsonResponse($data);
    }

    private function returnAsArrayTuples($data) {
        foreach ($data as $tupla){
            $result[] = [$tupla['name'], (int)$tupla['y']];
        }
        return $result;
    }
}