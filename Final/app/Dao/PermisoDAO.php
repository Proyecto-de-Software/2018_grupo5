<?php
/**
 * Created by PhpStorm.
 * User: seba
 * Date: 03/11/18
 * Time: 02:58
 */

class PermisoDAO extends DAO {

    public $model = "Permiso";

    function getByName($permission_name){
        return $this->findOneBy(['nombre' => $permission_name]);

    }

}