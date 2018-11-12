<?php

class ConsultaDAO extends DAO {

    public $model = "Consulta";

    public function getConsultasByPaciente($paciente){
        if ($paciente !=null && $paciente->getEliminado() == '0') {
            return $this->findBy(['paciente' => $paciente, 'eliminado'=>'0']);
        }
        return [];
    }

}
