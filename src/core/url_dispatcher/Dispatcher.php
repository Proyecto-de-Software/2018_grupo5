<?php
include_once(CODE_ROOT . "/controllers/includeAllControllers.php");
include_once(CODE_ROOT . "/core/errors/NotFound404.php");

class Dispatcher {
    private $urls;

    public function __construct($urls_array, $path_to_views) {
        $this->urls = $urls_array;
         load_all_controllers();
         $p = new Paciente_Controller();
    }

    public function run($url_request) {
        foreach ($this->urls as $path) {
            if ($path[0]->isThis($url_request) == true) {
                #TODO, ver si es necesario enviar un obj request como param, y como carajo se envia un param
                return call_user_func($path[1], $path[0]->getParameters($url_request));
            }
        }
        throw new NotFound404Exception("Error Processing Request", 1);

        return null;
    }

}
