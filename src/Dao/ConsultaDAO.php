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

    public function getGroupedByGender() {
        $qb = $this->entityManager()->createQueryBuilder();
        $qb->select('g.nombre as name', 'count(g.nombre) as y')
            ->from('Consulta', 'c')
            ->join('Paciente', 'p', 'WITH', 'c.paciente = p.id')
            ->join('Genero', 'g', 'WITH', 'g.id = p.genero')
            ->groupBy("g.nombre");
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function getGroupedByLocation() {
        $qb = $this->entityManager()->createQueryBuilder();
        $qb->select('l.nombre as name', 'count(l.nombre) as y')
            ->from('Consulta', 'c')
            ->join('Paciente', 'p', 'WITH', 'c.paciente = p.id')
            ->join('Localidad', 'l', 'WITH', 'l.id = p.localidad')
            ->groupBy("l.nombre");
        $query = $qb->getQuery();
        return $query->getResult();
    }
}