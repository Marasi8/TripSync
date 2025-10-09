<?php

namespace App\Repository;

use App\Entity\Viaje;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Viaje>
 */
class ViajeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Viaje::class);
    }

    //    /**
    //     * @return Viaje[] Returns an array of Viaje objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Viaje
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Devuelve viajes públicos (opcionalmente solo finalizados) con límite.
     *
     * @param int $limit
     * @param bool $soloFinalizados
     * @return Viaje[]
     */
    public function findPublicados(int $limit = 12, bool $soloFinalizados = true): array
    {
        $qb = $this->createQueryBuilder('v')
            ->andWhere('v.visibilidad = :pub')
            ->setParameter('pub', 'publico')
            ->orderBy('v.creado_en', 'DESC')
            ->setMaxResults($limit);

        if ($soloFinalizados) {
            $qb->andWhere('v.estado = :fin')->setParameter('fin', 'finalizado');
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Encuentra viajes donde el usuario participa (creados o como participante).
     *
     * @param string $usuarioId
     * @return Viaje[]
     */
    public function findViajesPorUsuario(string $usuarioId): array
    {
        return $this->createQueryBuilder('v')
            ->leftJoin('v.participantes', 'p')
            ->where('v.creado_por = :userId OR (p.usuario = :userId AND p.estado_participacion = :aceptado)')
            ->setParameter('userId', $usuarioId)
            ->setParameter('aceptado', 'aceptado')
            ->orderBy('v.actualizado_en', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Encuentra viajes por estado.
     *
     * @param string $estado
     * @param string|null $usuarioId
     * @return Viaje[]
     */
    public function findPorEstado(string $estado, ?string $usuarioId = null): array
    {
        $qb = $this->createQueryBuilder('v')
            ->where('v.estado = :estado')
            ->setParameter('estado', $estado)
            ->orderBy('v.fecha_inicio', 'ASC');

        if ($usuarioId) {
            $qb->leftJoin('v.participantes', 'p')
               ->andWhere('v.creado_por = :userId OR (p.usuario = :userId AND p.estado_participacion = :aceptado)')
               ->setParameter('userId', $usuarioId)
               ->setParameter('aceptado', 'aceptado');
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Busca viajes por título o descripción.
     *
     * @param string $termino
     * @param bool $soloPublicos
     * @return Viaje[]
     */
    public function buscarPorTermino(string $termino, bool $soloPublicos = false): array
    {
        $qb = $this->createQueryBuilder('v')
            ->where('v.titulo LIKE :termino OR v.descripcion LIKE :termino')
            ->setParameter('termino', '%' . $termino . '%')
            ->orderBy('v.creado_en', 'DESC');

        if ($soloPublicos) {
            $qb->andWhere('v.visibilidad = :pub AND v.estado = :fin')
               ->setParameter('pub', 'publico')
               ->setParameter('fin', 'finalizado');
        }

        return $qb->getQuery()->getResult();
    }
}
