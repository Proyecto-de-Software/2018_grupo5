<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/10/18
 * Time: 22:37
 */


class ConfiguracionDAO extends DAO {

    public $model = "Configuracion";

    function getConfigValue($variable) {
        $config = $this->findOneBy(['variable' => $variable]);
        $value = null;
        if(isset($config)) {
            $value = $config->getValor();
        }
        return $value;
    }

    function getConfigByName($name) {
        return $this->findOneBy(['variable' => $name]);
    }

}
