<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 03/09/18
 * Time: 22:56
 */

class Query {


    function __construct($db) {
        $this->statement = "";
    }

    function getSelect($tableName) {
        return $this;
    }

    function getDelete($tableName) {

    }

    function getUpdate($tableName) {

    }

    function addWhere() {

    }

    function orderedBy() {

    }

    function count() {

    }
    function naturalJoin() {

    }
}
