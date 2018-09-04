<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/08/18
 * Time: 19:28
 */

require_once("core/db/DB_Connection.php");
class DB extends Singleton {
    var $db;

    function __construct() {
      $host = "localhost";
      $username = "root";
      $password = "alumno";
      $dbname = "prueba";
      $this->db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
      }

    function debug_statement($stmt) {
      ob_start();
      $stmt->debugDumpParams();
      $r = ob_get_contents();
      ob_end_clean();
      return $r;
    }

    function db() {
        return $this->db;
    }

    function execute($obj_prepared_stmt) {
        if ($obj_prepared_stmt->execute()) {
            return true;
        } else {
            error_log( "Fallo la Query " . $this->debug_statement($obj_prepared_stmt));
            return false;
        }

    }

}
