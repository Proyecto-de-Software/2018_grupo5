<?php
require_once(CODE_ROOT . "/models/Consulta.php");
require_once(CODE_ROOT . "/models/Institucion.php");
require_once(CODE_ROOT . "/models/Paciente.php");

require_once(CODE_ROOT . "/Dao/DAO.php");


class ConsultaDAO extends DAO {

    public $model = "Consulta";

    public function getConsultasByPaciente($paciente){
    	return $this->findBy(['paciente' => $paciente, 'eliminado'=>'0']);
    }

}
