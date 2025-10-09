<?php

namespace App\Repository;

use App\Entity\AportacionBote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AportacionBote>
 */
class AportacionBoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AportacionBote::class);
    }

    /**
     * Calcula el total del bote común de un viaje.
     *
     * @param string $viajeId
     * @return float
     */
    public function calcularTotalBote(string $viajeId): float
    {
        $result = $this->createQueryBuilder('a')
            ->select('SUM(a.cantidad) as total')
            ->where('a.viaje = :viajeId')
            ->setParameter('viajeId', $viajeId)
            ->getQuery()
            ->getSingleScalarResult();

        return (float) ($result ?? 0);
    }

    /**
     * Calcula aportaciones por usuario en un viaje.
     *
     * @param string $viajeId
     * @return array
     */
    public function calcularAportacionesPorUsuario(string $viajeId): array
    {
        return $this->createQueryBuilder('a')
            ->select('u.id, u.nombre, u.username, SUM(a.cantidad) as total_aportado')
            ->join('a.usuario', 'u')
            ->where('a.viaje = :viajeId')
            ->setParameter('viajeId', $viajeId)
            ->groupBy('u.id, u.nombre, u.username')
            ->orderBy('total_aportado', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
