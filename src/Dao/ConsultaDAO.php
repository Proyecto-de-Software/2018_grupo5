<?php

class ConsultaDAO extends DAO {

    public $model = "Consulta";

    public function getConsultasByPaciente($paciente) {
        if($paciente != null && $paciente->getEliminado() == '0') {
            return $this->findBy(['paciente' => $paciente, 'eliminado' => '0']);
        }
        return [];
    }

    public function getGroupedByReason() {

        $qb = $this->entityManager()->createQueryBuilder();
        $qb->select('mc.nombre as name', 'count(c.motivo) as y')
            ->from('Consulta', 'c')
            ->join('MotivoConsulta', 'mc', 'WITH', 'c.motivo = mc.id')
            ->groupBy("c.motivo");
        $query = $qb->getQuery();
        return $query->getResult();
    }

}