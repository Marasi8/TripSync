<?php

namespace App\Repository;

use App\Entity\Propuesta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Propuesta>
 */
class PropuestaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Propuesta::class);
    }

    /**
     * Encuentra propuestas de un viaje por estado.
     *
     * @param string $viajeId
     * @param string|null $estado
     * @return Propuesta[]
     */
    public function findPorViaje(string $viajeId, ?string $estado = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->join('p.creado_por', 'u')
            ->where('p.viaje = :viajeId')
            ->setParameter('viajeId', $viajeId)
            ->orderBy('p.creado_en', 'DESC');

        if ($estado) {
            $qb->andWhere('p.estado = :estado')
               ->setParameter('estado', $estado);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Encuentra propuestas pendientes de revisión para el admin.
     *
     * @param string $viajeId
     * @return Propuesta[]
     */
    public function findPendientesRevision(string $viajeId): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.creado_por', 'u')
            ->where('p.viaje = :viajeId')
            ->andWhere('p.estado = :pendiente')
            ->setParameter('viajeId', $viajeId)
            ->setParameter('pendiente', 'en_revision')
            ->orderBy('p.creado_en', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Encuentra propuestas con votación activa.
     *
     * @param string $viajeId
     * @return Propuesta[]
     */
    public function findConVotacionActiva(string $viajeId): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.creado_por', 'u')
            ->where('p.viaje = :viajeId')
            ->andWhere('p.estado = :votacion')
            ->setParameter('viajeId', $viajeId)
            ->setParameter('votacion', 'en_votacion')
            ->orderBy('p.creado_en', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Cuenta propuestas por estado en un viaje.
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
            ->andWhere('p.estado = :estado')
            ->setParameter('viajeId', $viajeId)
            ->setParameter('estado', $estado)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
