<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 20/09/18
 * Time: 12:53
 */


require_once (CODE_ROOT . "/core/controller/Controller.php");

class IndexController extends Controller {

    public static function render(){
        echo "Hola mundo";
    }

}