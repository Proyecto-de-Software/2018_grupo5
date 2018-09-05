<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/08/18
 * Time: 19:05
 */
require_once('core/db/Objects.php');
require_once('core/db/Models.php');

abstract class Model_DB
{

    var $id;
    public $objects;

    function __construct() {
        $this->objects = new Objects($this);
        $this->id = new Models();
        $this->init();
    }

    abstract function init();

    public function get_class() {
        return get_class($this);
    }

    public function get_model_columns()  {
        $all_vars = array_keys(get_class_vars(get_class($this)));
        $column_vars = array_filter($all_vars, function ($i) {return ($this->$i instanceof Models) ;});
        return $column_vars;
    }

    public function getAttribute($variable) {
      return $this->$variable;
    }


    public static function get_table_name() {
        //FIXME: testear, y arreglar seguramente :p
        preg_match("([a-zA-Z]+\S+)_DB", get_called_class(), $coincidencias);
        return $coincidencias[0];
    }


}
