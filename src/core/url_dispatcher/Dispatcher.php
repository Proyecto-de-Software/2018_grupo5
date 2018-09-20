<?php
include_once(CODE_ROOT . "/controllers/includeAllControllers.php");
include_once(CODE_ROOT . "/core/errors/NotFound404.php");

class Dispatcher {
    private $urls;

    public function __construct($urls_array, $path_to_views) {
        $this->urls = $urls_array;
         load_all_controllers();
    }

    public function run($url_request) {
        foreach ($this->urls as $path) {
            /** @var Path $path  */
            if ($path->matcher()->isThis($url_request) == true) {
                #TODO, ver si es necesario enviar un obj request como param, y como carajo se envia un param
                return $path->exec($url_request);
            }
        }
        throw new NotFound404Exception("Error Processing Request", 1);
    }

}
