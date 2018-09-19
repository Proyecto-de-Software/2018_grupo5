<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 04/09/18
 * Time: 20:40
 */

class Dispatcher {
    private $urls;

    public function __construct($urls_array, $path_to_views) {
        #todo: hacer un for, con todos los files que esten en la carpeta /views
        $this->urls = $urls_array;
    }

    public function run($url_request) {
        foreach ($this->urls as $path) {
            if ($path[0]->isThis($url_request) == true) {
                #TODO, ver si es necesario enviar un obj request como param, y como carajo se envia un param
                return call_user_func($path[1], $path[0]->getParameters($url_request));
            }

        }
        return null;
    }

}