<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 23/09/18
 * Time: 15:46
 */

require_once(CODE_ROOT . "/controllers/Controller.php");
include_once(CODE_ROOT . "/models/Permiso.php");
include_once(CODE_ROOT . "/models/Configuracion.php");

use controllers\Controller;

class SetupDbDataController extends Controller {

    private function loadDataFromApi($url, $model) {
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
                eval($c);

                try {
                    $this->entityManager()->persist($model);
                    $this->entityManager()->flush();
                } catch (Exception $e) {
                    echo "<pre> -- Error running  - may be just exisits</pre>";
                }

            }
        }

    }

    function loadData(...$args) {
        $this->assertPermission();
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
                    $is_created = $this->saveNewPermission($permission_name);
                    echo '<pre>   --- ' . $permission_name . ' --> ' . ($is_created ? 'Created' : 'Failed, may be exists') . '</pre>';
                }
            }
        }
        $time = time() - $_SERVER['REQUEST_TIME'];
        echo "<h2> Took $time milliseconds to complete the taks. </h2>";
    }

    private function saveNewPermission($permission_name) {
        try {
            $perm = new Permiso();
            $perm->setNombre($permission_name);
            $this->entityManager()->persist($perm);
            $this->entityManager()->flush();
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

        foreach ($configs as $variable => $value) {
            $config = $this->getModel('Configuracion')->findOneBy(['variable' => $variable]);
            if(!isset($config)) {
                $this->saveConfig($variable, $value);
            } else {
                echo "<p> Ya existe la config para $variable </p>";
            }
        }
    }

    private function saveConfig($variable, $value) {
        try {
            $c = new Configuracion();
            $c->setValor($value);
            $c->setVariable($variable);
            $this->entityManager()->persist($c);
            $this->entityManager()->flush();
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
                    $hasAssertPermission = $this->hasContentInFile(
                        $method->getFileName(),
                        '->assertPermission();',
                        $method->getStartLine(),
                        $method->getEndLine()
                    );

                    $hasAssertInMaintenance = $this->hasContentInFile(
                        $method->getFileName(),
                        '->assertInMaintenance();',
                        $method->getStartLine(),
                        $method->getEndLine()
                    );


                    echo '<pre>   --- ' . $method->getName() . ' --> ';
                    echo ($hasAssertPermission ? ' Analiza permisos. ' : ' *Este metodo no controla los permisos!** ') ;
                    echo ($hasAssertInMaintenance ? ' Controla mantenimiento. ' : ' *Este metodo no controla si el sitio esta en manteniemto!** ') ;
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
                if($i >= $start && $i <= $end) {
                    if(strpos($line, '->assertPermission();') !== false) {
                        return true;
                    }
                }
            }
            fclose($fh);
        }
        return false;
    }
}