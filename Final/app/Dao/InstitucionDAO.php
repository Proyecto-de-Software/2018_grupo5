<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/10/18
 * Time: 22:37
 */


class InstitucionDAO extends DAO {

    public $model = "Institucion";

    function findByRegionSanitariaId($id_region_santiaria) {
        return $this->findBy(['regionSanitaria' => $id_region_santiaria]);
    }

}
