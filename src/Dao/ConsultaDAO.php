<?php

class ConsultaDAO extends DAO {

    public $model = "Consulta";

    public function getConsultasByPaciente($paciente){
    	return $this->findBy(['paciente' => $paciente, 'eliminado'=>'0', 'paciente.getEliminado' => '0']);
    }

}
