<?php

namespace App\Repository;

use App\Entity\Participante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Participante>
 */
class ParticipanteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participante::class);
    }

    /**
     * Encuentra participantes aceptados de un viaje.
     *
     * @param string $viajeId
     * @return Participante[]
     */
    public function findParticipantesAceptados(string $viajeId): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.usuario', 'u')
            ->where('p.viaje = :viajeId')
            ->andWhere('p.estado_participacion = :aceptado')
            ->setParameter('viajeId', $viajeId)
            ->setParameter('aceptado', 'aceptado')
            ->orderBy('p.unido_en', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Encuentra administradores de un viaje.
     *
     * @param string $viajeId
     * @return Participante[]
     */
    public function findAdministradores(string $viajeId): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.usuario', 'u')
            ->where('p.viaje = :viajeId')
            ->andWhere('p.es_admin = :esAdmin')
            ->andWhere('p.estado_participacion = :aceptado')
            ->setParameter('viajeId', $viajeId)
            ->setParameter('esAdmin', true)
            ->setParameter('aceptado', 'aceptado')
            ->orderBy('p.unido_en', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Verifica si un usuario participa en un viaje.
     *
     * @param string $viajeId
     * @param string $usuarioId
     * @return Participante|null
     */
    public function findParticipacion(string $viajeId, string $usuarioId): ?Participante
    {
        return $this->createQueryBuilder('p')
            ->where('p.viaje = :viajeId')
            ->andWhere('p.usuario = :usuarioId')
            ->setParameter('viajeId', $viajeId)
            ->setParameter('usuarioId', $usuarioId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Cuenta participantes por estado.
     *
     * @param string $viajeId
     * @param string $estado
     * @return int
     */
    public function contarPorEstado(string $viajeId, string $estado): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.viaje = :viajeId')
            ->andWhere('p.estado_participacion = :estado')
            ->setParameter('viajeId', $viajeId)
            ->setParameter('estado', $estado)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
