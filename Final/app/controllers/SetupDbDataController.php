<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 23/09/18
 * Time: 15:46
 */

require_once("Controller.php");

use controllers\Controller;

class SetupDbDataController extends Controller {

    private function loadDataFromApi($url, $model) {
        $partidoDao = new PartidoDao();

        $data = json_decode(file_get_contents($url));
        require_once(CODE_ROOT . "/models/" . $model . ".php");
        echo "<h3> Load data for model <em>$model</em> from <em>$url</em>  </h3> ";
        foreach ($data as $d) {
            $model = new $model();
            foreach ($d as $key => $value) {
                if($key == "id") {
                    continue;
                }

                $key = ucfirst($key);
                $c = "\$model->set" . $key . "('" . $value . "');";
                echo "<pre>**About to run: <strong>$c</strong></pre>";

                try {
                    eval($c);
                    $partidoDao->persist($model);
                } catch (Exception $e) {
                    echo "<pre> -- Error running  - may be just exisits</pre>";
                }

            }
        }

    }


    private function loadComplexDataFromApi() {
        $url = 'https://api-referencias.proyecto2018.linti.unlp.edu.ar/partido';
        $model = 'Partido';
        $data = json_decode(file_get_contents($url), true);

        require_once(CODE_ROOT . "/models/" . $model . ".php");
        echo "<h3> Load data for model <em>$model</em> from <em>$url</em>  </h3> ";
        foreach ($data as $d) {
            $model = new $model();
            //Si no existe el partido, lo creo y cargo la reg sanit y sus localidades
            $partidoDao = new PartidoDao();
            if(!$partidoDao->getByName($d['nombre'])) {
                $model->setNombre($d['nombre']);

                $id_partido_api = $d['id'];
                $id_region_sanitaria = $d['region_sanitaria_id'];

                $url1 = 'https://api-referencias.proyecto2018.linti.unlp.edu.ar/region-sanitaria/' . $id_region_sanitaria;
                $model1 = 'RegionSanitaria';
                $data1 = json_decode(file_get_contents($url1), true);

                $model1 = new $model1();

                $model1->setNombre($data1['nombre']);

                $model->setRegionSanitaria($model1);


                $url2 = 'https://api-referencias.proyecto2018.linti.unlp.edu.ar/localidad/partido/' . $id_partido_api;
                $model2 = 'Localidad';
                $data2 = json_decode(file_get_contents($url2), true);
                foreach ($data2 as $d2) {
                    $model2 = new $model2();
                    $model2->setNombre($d2['nombre']);
                    $model2->setPartido($model);
                }
                $partidoDao->persist($model1);
                $partidoDao->persist($model);
                $partidoDao->persist($model2);
            }
        }


    }


    function loadData(...$args) {
        $this->assertPermission();

        $this->loadComplexDataFromApi();

        echo "<html lang=\"en\"><h2>Load data from api Linti</h2>";

        $this->loadDataFromApi(
            "https://api-referencias.proyecto2018.linti.unlp.edu.ar/obra-social",
            "ObraSocial"
        );

        $this->loadDataFromApi(
            "https://api-referencias.proyecto2018.linti.unlp.edu.ar/tipo-documento",
            "TipoDocumento"
        );


        $time = time() - $_SERVER['REQUEST_TIME'];
        echo "<h2> Took $time milliseconds to complete the taks. </h2>";
    }

    function generatePermissionData(...$args) {
        $this->assertPermission();

        echo "<html lang=\"en\"><h1>Create permissions</h1>";
        $controllers = (glob(CODE_ROOT . '/controllers/*Controller.php'));
        foreach ($controllers as $controller) {
            $ok = preg_match("/.+\/([A-Z][a-zA-Z]+Controller).php/", $controller, $matches);
            if($ok) {
                $class_name = $matches[1];
                require_once($controller);
                $reflection = new ReflectionClass($class_name);

                $methods = array_filter(
                    $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                    function ($o)
                    use ($reflection) {
                        return $o->class == $reflection->getName();
                    }
                );
                echo "<h4>$class_name</h4>";
                foreach ($methods as $method) {
                    $permission_name = $this->generatePermissionName($class_name, $method->getName());
                    /**@doc: avoid create permission for magic and constructor methods.* */
                    if(preg_match("/.*__.*/", $permission_name)) {
                        continue;
                    }
                    $is_created = $this->saveNewPermission($permission_name);
                    echo '<pre>   〄 ' . $permission_name . ' --> ' . ($is_created ? '✔ Created' : '✘ Failed, may be exists') . '</pre>';
                }
            }
        }
        $time = time() - $_SERVER['REQUEST_TIME'];
        echo "<h2> Took $time milliseconds to complete the taks. </h2>";
    }

    private function saveNewPermission($permission_name) {
        try {
            $permisoDao = new PermisoDAO();
            $perm = new Permiso();
            $perm->setNombre($permission_name);
            $permisoDao->persist($perm);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function createDefaultConfigs() {
        $this->assertPermission();

        $configs = [
            'titulo' => 'Titulo',
            'sitio_activo' => 'true',
        ];
        $configuracionDao = new ConfiguracionDAO();
        foreach ($configs as $variable => $value) {
            $config = $configuracionDao->getConfigByName($variable);
            if(!isset($config)) {
                $this->saveConfig($configuracionDao, $variable, $value);
            } else {
                echo "<p> Ya existe la config para $variable </p>";
            }
        }
    }

    private function saveConfig(&$dao, $variable, $value) {
        try {
            $c = new Configuracion();
            $c->setValor($value);
            $c->setVariable($variable);
            $dao->persist($c);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    public function showWarnings() {
        $this->assertPermission();

        echo "<html lang=\"en\"><h1>Chequeo de codigo</h1>";
        $controllers = (glob(CODE_ROOT . '/controllers/*Controller.php'));
        foreach ($controllers as $controller) {
            $ok = preg_match("/.+\/([A-Z][a-zA-Z]+Controller).php/", $controller, $matches);
            if($ok) {
                $class_name = $matches[1];
                require_once($controller);
                $reflection = new ReflectionClass($class_name);

                $methods = array_filter(
                    $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                    function ($o)
                    use ($reflection) {
                        return $o->class == $reflection->getName();
                    }
                );
                echo "<h4>$class_name</h4>";
                foreach ($methods as $method) {
                    if($method->getName() == '__construct') {
                        continue;
                    }
                    $hasAssertPermission = $this->hasContentInFile(
                        $method->getFileName(),
                        '->assertPermission',
                        $method->getStartLine(),
                        $method->getEndLine()
                    );

                    $hasAssertPermission = $this->hasContentInFile(
                            $method->getFileName(),
                            '->userHasPermissionForCurrentMethod()',
                            $method->getStartLine(),
                            $method->getEndLine()
                        ) | $hasAssertPermission;


                    $hasAssertInMaintenance = $this->hasContentInFile(
                        $method->getFileName(),
                        '->assertInMaintenance();',
                        $method->getStartLine(),
                        $method->getEndLine()
                    );


                    echo '<pre>   〄 ' . $method->getName() . ' --> ';
                    echo($hasAssertPermission ? ' ✔ Analiza permisos.   ' : ' ✘ Este metodo no controla los permisos! ');
                    echo($hasAssertInMaintenance === true ? ' ✔ Controla mantenimiento. ' : ' ✘ Este metodo no controla si el sitio esta en manteniemto! ');
                    echo '</pre>';
                }
            }
        }
        $time = time() - $_SERVER['REQUEST_TIME'];
        echo "<h2> Took $time milliseconds to complete the taks. </h2>";
    }

    private function hasContentInFile($filePath, $search, $start, $end) {
        if($fh = fopen($filePath, 'r')) {
            $i = 0;
            while (!feof($fh)) {
                $i++;
                $line = fgets($fh);
                if(($i >= $start && $i <= $end) && (strpos($line, $search) !== false)) {
                    fclose($fh);
                    return true;
                }
            }
            fclose($fh);
        }
        return false;
    }
}