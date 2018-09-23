<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 23/09/18
 * Time: 15:46
 */

require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class SetupController extends Controller {

    function loadDataFromApi($url, $model) {
        $data = json_decode(file_get_contents($url));
        require_once(CODE_ROOT . "/models/" . $model . ".php");
        echo "<h3> Load data for model < $model >, consumed in < $url > </h3> ";
        foreach ($data as $d) {
            $model = new $model();
            foreach ($d as $key => $value) {
                if($key == "id") {
                    continue;
                }
                $key = ucfirst($key);
                $c = "\$model->set" . $key . "('" . $value . "');";
                echo "<p>**About to run: <strong>$c</strong></p>";
                eval($c);

                try{
                    $this->entityManager()->persist($model);
                    $this->entityManager()->flush();
                }catch (Exception $e){
                    echo "<h3>Error running <strong>$c</strong> </h3><p>$e</p>";
                }

            }
        }


    }

    static function render(...$args) {
        echo "<html lang=\"en\"><h1>Setup models</h1>";
        $instance = new SetupController();

        $instance->loadDataFromApi(
            "https://api-referencias.proyecto2018.linti.unlp.edu.ar/obra-social",
            "ObraSocial"
        );

        $instance->loadDataFromApi(
            "https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento",
            "TipoDocumento"
        );


    }

}