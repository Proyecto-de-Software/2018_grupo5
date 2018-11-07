<?php
/**
 * Created by PhpStorm.
 * User: seba
 * Date: 03/11/18
 * Time: 02:50
 */

class PartidoDao extends DAO {

    public $model = "Partido";

    function getByName($name) {
        return $this->getModel('Partido')->findOneBy(['nombre' => $name]);
    }
}