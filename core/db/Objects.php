<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 03/09/18
 * Time: 20:14
 */
 require_once('core/db/DB.php');

class Objects extends DB {

    private $model;
    private $table_name;

    function __construct($modelInstance) {
        parent::__construct();
        $this->model = $modelInstance;
        $this->table_name = $modelInstance->get_table_name();
    }

    /** this method is for use the  binding of prepared queries **/
    private static function create_bind_param($string) {
        return ":" . $string;
    }

    function create($parameters, $is_for_api=false) {

        $columns = $this->model->get_model_columns();

        $statement = $this->db()->prepare (  "INSERT INTO  " . $this->table_name .
            " (" . implode(', ', $columns) . ")" .
            " VALUES (" . implode(', ', array_map(array($this, "create_bind_param"), $columns)) . ")");

        /*Iterate over the models columns*/
        foreach ($columns as $column) {
            $column_model_obj = $this->model->$column;
            if ($column_model_obj->isApiAccessible() || !$is_for_api) {
                if (array_key_exists($column, $parameters)) {
                    $column_value = $parameters[$column];
                } elseif ($column_model_obj->getDefault()) {
                    $column_value = $column_model_obj->getDefault();
                } else {
                    error_log("Not found attribute for column: < $column > on table < $this->table_name >");
                    return false;
                }
                if ($column_model_obj->isValidData($column_value) == false) {
                    error_log("Not valid value for attribute: < $column_model_obj -> $column_value >");
                    return false;
                }
                $statement->bindParam($this::create_bind_param($column), $column_value);
            }
        }
        return $this->execute($statement);
    }

    function delete_by_id($id){
        $statement = $this->db()->prepare ("DELETE FROM " . $this->table_name . " WHERE id=". $id );
        return $this->execute($statement);
    }

    private function run_query_and_return_array_of_related_objects($statement) {
        $this->execute($statement);
        $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
        $map_array = array();
        $clazz = $this->model->get_class();
        foreach ($statement->fetchAll() as $row) {
            $model_object = new $clazz();
            $map_array[] = $model_object;
            foreach ($row as $column=>$value) {
                if ($model_object->$column != null){
                  $model_object->$column->setValue($value);
                }
            }
        }
        return $map_array;
    }

    function get_by_id($id) {
        $statement = $this->db()->prepare("SELECT * FROM " . $this->table_name  . " WHERE id=" . $id);
        return $this->run_query_and_return_array_of_related_objects($statement);
    }

}
