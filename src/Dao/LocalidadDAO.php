<?php
/**
 * Created by PhpStorm.
 * User: seba
 * Date: 03/11/18
 * Time: 02:50
 */

class LocalidadDAO extends DAO {

    public $model = "Localidad";

    function findByPartido(Partido $partido) {
        $this->findOneBy(['partido' => $partido]);
    }
}