<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/08/18
 * Time: 19:05
 */
require_once('core/db/DB.php');
require_once('core/model/Objects.php');

abstract class Model_DB extends DB
{

    var $id;
    var $models;
    public $objects;

    function __construct() {
        parent::__construct();
        $this->objects = new Objects($this);
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



    abstract function get_table_name();


}