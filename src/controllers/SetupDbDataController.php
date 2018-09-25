<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 23/09/18
 * Time: 15:46
 */

require_once(CODE_ROOT . "/controllers/Controller.php");

use controllers\Controller;

class SetupDbDataController extends Controller {

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

    static function loadData(...$args) {
        echo "<html lang=\"en\"><h1>Setup models</h1>";
        $instance = new SetupDbDataController();

        $instance->loadDataFromApi(
            "https://api-referencias.proyecto2018.linti.unlp.edu.ar/obra-social",
            "ObraSocial"
        );

        $instance->loadDataFromApi(
            "https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento",
            "TipoDocumento"
        );
    }


    static function generatePermissionData(...$args) {
        echo "<html lang=\"en\"><h1>Create permissions</h1>";
        $instance = new SetupDbDataController();

        $controllers = (glob(CODE_ROOT . '/controllers/*Controller.php'));
        foreach ($controllers as $controller) {
            $ok = preg_match("/.+\/([A-Z][a-zA-Z]+Controller).php/", $controller, $matches);
            if ($ok) {
                $class_name = $matches[1];
                require_once ($controller);
                $reflection = new ReflectionClass($class_name);
                $methods = array_filter(
                    $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                    function ($o)
                    use ($reflection) {return $o->class == $reflection->getName();}
                    );
                echo "<h3> $class_name </h3>";
                foreach ($methods as $method){
                    echo '<pre>   --- ' . $method->getName() . '</pre>';
                }
            }
        }



    }

}