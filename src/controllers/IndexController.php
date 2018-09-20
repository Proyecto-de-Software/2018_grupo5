<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 20/09/18
 * Time: 12:53
 */

use core\controller\Controller;

echo "incluyendo index ";

class IndexController extends Controller{

    public static function render(){
        echo "Hola mundo";
    }

}