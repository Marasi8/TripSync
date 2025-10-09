<?php

namespace App\Repository;

use App\Entity\Gasto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gasto>
 */
class GastoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gasto::class);
    }

    /**
     * Encuentra gastos de un viaje ordenados por fecha.
     *
     * @param string $viajeId
     * @return Gasto[]
     */
    public function findPorViaje(string $viajeId): array
    {
        return $this->createQueryBuilder('g')
            ->join('g.pagador', 'u')
            ->where('g.viaje = :viajeId')
            ->setParameter('viajeId', $viajeId)
            ->orderBy('g.fecha', 'DESC')
            ->addOrderBy('g.creado_en', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Calcula el total de gastos de un viaje.
     *
     * @param string $viajeId
     * @return float
     */
    public function calcularTotalGastos(string $viajeId): float
    {
        $result = $this->createQueryBuilder('g')
            ->select('SUM(g.cantidad) as total')
            ->where('g.viaje = :viajeId')
            ->setParameter('viajeId', $viajeId)
            ->getQuery()
            ->getSingleScalarResult();

        return (float) ($result ?? 0);
    }

    /**
     * Encuentra gastos por categoría en un viaje.
     *
     * @param string $viajeId
     * @param string $categoria
     * @return Gasto[]
     */
    public function findPorCategoria(string $viajeId, string $categoria): array
    {
        return $this->createQueryBuilder('g')
            ->join('g.pagador', 'u')
            ->where('g.viaje = :viajeId')
            ->andWhere('g.categoria = :categoria')
            ->setParameter('viajeId', $viajeId)
            ->setParameter('categoria', $categoria)
            ->orderBy('g.fecha', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Calcula gastos por usuario en un viaje.
     *
     * @param string $viajeId
     * @return array
     */
    public function calcularGastosPorUsuario(string $viajeId): array
    {
        return $this->createQueryBuilder('g')
            ->select('u.id, u.nombre, u.username, SUM(g.cantidad) as total_pagado')
            ->join('g.pagador', 'u')
            ->where('g.viaje = :viajeId')
            ->setParameter('viajeId', $viajeId)
            ->groupBy('u.id, u.nombre, u.username')
            ->orderBy('total_pagado', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Encuentra gastos compartidos de un viaje.
     *
     * @param string $viajeId
     * @return Gasto[]
     */
    public function findGastosCompartidos(string $viajeId): array
    {
        return $this->createQueryBuilder('g')
            ->join('g.pagador', 'u')
            ->where('g.viaje = :viajeId')
            ->andWhere('g.es_compartido = :compartido')
            ->setParameter('viajeId', $viajeId)
            ->setParameter('compartido', true)
            ->orderBy('g.fecha', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Calcula gastos por categoría en un viaje.
     *
     * @param string $viajeId
     * @return array
     */
    public function calcularGastosPorCategoria(string $viajeId): array
    {
        return $this->createQueryBuilder('g')
            ->select('g.categoria, SUM(g.cantidad) as total')
            ->where('g.viaje = :viajeId')
            ->setParameter('viajeId', $viajeId)
            ->groupBy('g.categoria')
            ->orderBy('total', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
