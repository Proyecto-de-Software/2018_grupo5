<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/10/18
 * Time: 22:37
 */

require_once(CODE_ROOT . "/Dao/DAO.php");

class InstitucionDAO extends DAO {

    public $model = "Institucion";

    function findByRegionSantiariaId($id_region_santiaria) {
        return $this->getModel()->findBy(['regionSanitaria' => $id_region_santiaria]);
    }

}
