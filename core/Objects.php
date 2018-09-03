<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 03/09/18
 * Time: 20:14
 */

class Objects extends DB {

    private $model;

    function __construct($model) {
        parent::__construct();
        $this->model = $model;
    }

    /** this method is for use the  binding of prepared queries **/
    private static function create_bind_param($string) {
        return ":" . $string;
    }


    function create($parameters, $is_for_api=false) {
        $table_name = $this->model->get_table_name();
        $columns = $this->model->get_model_columns();

        $statement = $this->db()->prepare (  "INSERT INTO  " . $table_name .
            " (" . implode(', ', $columns) . ")" .
            " VALUES (" . implode(', ', array_map(array($this, "create_bind_param"), $columns)) . ")");

        /*Iterate over the models columns*/
        foreach ($columns as $column) {
            $column_model_obj = $this->model->$column;

            if ($column_model_obj->isApiAccessible() || !$is_for_api) {

                if (array_key_exists($column, $parameters)){
                    $column_value = $parameters[$column];
                } elseif ($column_model_obj->getDefault()){
                    $column_value = $column_model_obj->getDefault();
                } else {
                    error_log("Not found attribute for column: < $column > on table < $table_name >");
                    return false;
                }

                if ($column_model_obj->isValidData($column_value) == false){
                    error_log("Not valid value for attribute: < $column_model_obj -> $column_value >");
                    return false;
                }
                $statement->bindParam($this::create_bind_param($column_value), $column_value);

            }
        }

        $statement->execute();
        return true;
    }



    function get_by_id($id) {

        $statement = $this->db()->prepare("SELECT * from " . $this->get_table_name() . "where id=" . $id);
        $statement->execute();


        $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
        foreach (new RecursiveArrayIterator($statement->fetchAll()) as $key => $value) {

        }
    }

}