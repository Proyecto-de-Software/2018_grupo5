<?php
include_once(CODE_ROOT . "/core/errors/NotFound404.php");

class Dispatcher {
    private $urls;

    public function __construct($urls_array, $path_to_views) {
        $this->urls = $urls_array;
    }

    /**
     * @param $url_request
     * @return mixed
     * @throws NotFound404Exception
     * @throws BadControllerNameException
     */
    public function run($url_request) {
        foreach ($this->urls as $path) {

            /** @var Path $path  */
            if ($path->isThis($url_request) == true) {
                return $path->exec($url_request);
            }
        }
        throw new NotFound404Exception("Error Processing Request", 1);
    }

}
