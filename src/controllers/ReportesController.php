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
}