<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 20/09/18
 * Time: 12:53
 */

include_once (CODE_ROOT . "/core/controller/Controller.php");
echo CODE_ROOT . "/core/controller/Controller.php ";
echo "incluyendo index ";

class IndexController extends Controller{

    public static function render(){
        echo "Hola mundo";
    }

}