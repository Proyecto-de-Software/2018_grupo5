<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/08/18
 * Time: 19:05
 */
require_once('DB.php');
abstract class Model extends DB
{

    var $models;

    function __construct() {
        parent::__construct();
        $this->models = new Models();
        $this->init();
    }

    abstract function init();

    private function get_atrributes()  {
        return array_keys(get_class_vars(get_class($this)));
    }

    /** this method is for use the  binding of prepared queries **/
    private function create_bind_param($s) {
        return ":" . $s;
    }

    function create($parameters, $is_for_api=false) {
        $attributes = $this->get_atrributes();
        $statement = $this->db()->prepare (  "INSERT INTO  " . $this->get_table_name() .
            " (" . implode(', ', $attributes) . ")" .
            " VALUES (" . implode(', ', array_map(array($this, "create_bind_param"), $attributes)) . ")");
        /*Iterate over the models attributes*/
        foreach ($attributes as $attribute) {
            $modelAttribute = $this->$attribute;

            if ($modelAttribute->isApiAccessible() || !$is_for_api) {

                if (array_key_exists($attribute, $parameters)){
                    $attr_value = $parameters[$attribute];
                } elseif ($modelAttribute->getDefault()){
                    $attr_value = $modelAttribute->getDefault();
                } else {
                    error_log("Not found attribute < $attribute >");
                    return false;
                }

                if ($modelAttribute->isValidData($attr_value) == false){
                    error_log("Not valid value for attribute: < $modelAttribute -> $attr_value >");
                    return false;
                }
                $statement->bindParam($this->create_bind_param($attribute), $attr_value);

            }
        }
        echo $statement;
        return true;
    }

    function delete()
    {

    }

    function update()
    {

    }

    abstract function get_table_name();


}