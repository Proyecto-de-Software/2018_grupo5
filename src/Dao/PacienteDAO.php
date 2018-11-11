<?php
/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 30/10/18
 * Time: 22:37
 */

require_once(CODE_ROOT . "/Dao/DAO.php");
require_once(CODE_ROOT . "/Dao/PermisoDAO.php");

class PacienteDAO extends DAO {

    public $model = "Paciente";

    public function searchPacientes($nombre, $apellido, $tipo_doc, $doc_numero, $numeroHistorioClinica, $deleted){

        $qb = $this->entityManager()->createQueryBuilder();
        $qb->select('p')
            ->from('Paciente', 'p')
            ->where($qb->expr()->orX(
                $qb->expr()->like('p.nombre', '?1'),
                $qb->expr()->like('p.apellido', '?2'),
                $qb->expr()->andX(
                    $qb->expr()->eq('p.tipoDoc', '?3'),
                    $qb->expr()->eq('p.numero', '?4')
                ),
                $qb->expr()->eq('p.nroHistoriaClinica', '?5')
            ),
                $qb->expr()->andX(
                    $qb->expr()->eq('p.eliminado', '?6')
                )
            );
        $parameters =
            [
                1 => '',
                2 => '',
                3 => $tipo_doc,
                4 => $doc_numero,
                5 => $numeroHistorioClinica,
                6 => $deleted,
            ];

        $parameters[1] = ($nombre !== "") ? "%" . $nombre . "%" : $nombre;
        $parameters[2] = ($apellido !== "") ? "%" . $apellido . "%" : $apellido;

        $qb->setParameters($parameters);
        $query = $qb->getQuery();
        return $query->getResult();
    }

}
