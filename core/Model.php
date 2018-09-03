<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/08/18
 * Time: 19:05
 */
require_once('DB.php');
require_once ('Objects.php');

abstract class Model_DB extends DB
{

    var $models;
    public $objects;

    function __construct() {
        parent::__construct();
        $this->objects = new Objects($this);
        $this->init();
    }

    abstract function init();

    public function get_model_columns()  {
        $all_vars = array_keys(get_class_vars(get_class($this)));
        $column_vars = array_filter($all_vars, function ($i) {return ($i instanceof Models);});
        return $column_vars;
    }



    abstract function get_table_name();


}