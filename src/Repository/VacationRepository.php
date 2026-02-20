<?php

namespace App\Repository;

use DateTimeImmutable;
use App\Entity\Vacation;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Vacation>
 */
class VacationRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Vacation::class);
    }

    public function findTodayAndUpcomingVacations(): array {
        $qb = $this->createQueryBuilder('v');
        $qb->where('v.date >= :today')
            ->setParameter('today', new DateTimeImmutable('today'))
            ->orderBy('v.date', 'ASC');

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Vacation[] Returns an array of Vacation objects
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

    //    public function findOneBySomeField($value): ?Vacation
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
