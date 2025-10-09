<?php

namespace App\Repository;

use App\Entity\Destino;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Destino>
 */
class DestinoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Destino::class);
    }

    /**
     * Encuentra el orden máximo para un viaje.
     *
     * @param string $viajeId
     * @return int
     */
    public function findMaxOrdenPorViaje(string $viajeId): int
    {
        $result = $this->createQueryBuilder('d')
            ->select('MAX(d.orden) as max_orden')
            ->where('d.viaje = :viajeId')
            ->setParameter('viajeId', $viajeId)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) ($result ?? 0);
    }

    /**
     * Encuentra destinos de un viaje ordenados.
     *
     * @param string $viajeId
     * @return Destino[]
     */
    public function findDestinosPorViaje(string $viajeId): array
    {
        return $this->createQueryBuilder('d')
            ->where('d.viaje = :viajeId')
            ->setParameter('viajeId', $viajeId)
            ->orderBy('d.orden', 'ASC')
            ->addOrderBy('d.fecha_llegada', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtiene el último orden para un viaje específico.
     *
     * @param \App\Entity\Viaje $viaje
     * @return int
     */
    public function getUltimoOrden(\App\Entity\Viaje $viaje): int
    {
        $result = $this->createQueryBuilder('d')
            ->select('MAX(d.orden) as max_orden')
            ->where('d.viaje = :viaje')
            ->setParameter('viaje', $viaje)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) ($result ?? 0);
    }
}
