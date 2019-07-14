<?php
include_once(CODE_ROOT . "/core/errors/NotFound404.php");
include_once(FW_CODE_ROOT . "/utils/Singleton.php");

class Dispatcher {
    private $paths;

    function __construct() {
        $this->paths = [];
    }

    /**
     * @param $url_request
     * @return mixed
     * @throws NotFound404Exception
     * @throws BadControllerNameException
     */

    public function run($url_request) {
        foreach ($this->paths as $path) {
            /** @var Path $path */
            if($path->isThis($url_request)) {
                return $path->exec($url_request);
            }
        }
        if(DEBUG) {
            $this->show_available_urls();
        }
        throw new NotFound404Exception("Dispatcher don't found a resource that matching $url_request", 1);
    }

    public function show_available_urls() {
        echo "<html lang=\"en\"><h1>Not found - Available Urls</h1><h4>You are seeing this because DEBUG=true</h4><ul>";
        foreach ($this->paths as $path) {
            echo "<li>" . htmlspecialchars($path->getUrlPattern()) . "</li>\n";
        }
        echo "</ul></html>";
    }


    public function addPaths($path_array) {
        $this->paths = array_merge($path_array, $this->paths);
    }

    /**
     * @param Path $path
     */
    public function addPath($path) {
        $this->paths[] = $path;
    }

}
