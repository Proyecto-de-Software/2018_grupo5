<?php
require_once(CODE_ROOT . "/models/Consulta.php");
require_once(CODE_ROOT . "/models/Institucion.php");
require_once(CODE_ROOT . "/models/Paciente.php");

require_once(CODE_ROOT . "/Dao/DAO.php");


class ConsultaDAO extends DAO {

    public $model = "Consulta";

    
    public function setPaciente($paciente){
         $this->getModel()->setPaciente($paciente);
    }

    public function setFecha($fecha){
         $this->getModel()->setFecha($fecha);
    }
    
    public function setMotivo($motivo){
         $this->getModel()->setMotivo($motivo);
    }

    public function setDerivacion($derivacion){
         $this->getModel()->setDerivacion($derivacion);
    }

    public function setArticulacion($articulacion){
         $this->getModel()->setArticulacion($articulacion);
    }

    public function setInternacion($internacion){
         $this->getModel()->setInternacion($internacion);
    }

  
    public function setDiagnostico($diagnostico){
         $this->getModel()->setDiagnostico($diagnostico);
    }

    public function setObservaciones($observaciones){
         $this->getModel()->setObservacionessetObservaciones($observaciones);
    }

    public function setTratamientoFarmacologico($tratamiento_farmacologico){
         $this->getModel()->setTratamientoFarmacologico($tratamiento_farmacologico);
    }

    public function setAcompanamiento($acompanamiento){
         $this->getModel()->setAcompanamiento($acompanamiento);
    }

}
