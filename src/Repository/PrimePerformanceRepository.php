<?php

namespace App\Repository;

use App\Entity\PrimePerformance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PrimePerformance|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrimePerformance|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrimePerformance[]    findAll()
 * @method PrimePerformance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrimePerformanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrimePerformance::class);
    }

    public function findByDateRange($id, \DateTime $startOfMonth, \DateTime $endOfDate)
    {
        return $this->createQueryBuilder('p')
            ->where('p.employe = :id')
            ->andWhere('p.createAt BETWEEN :start AND :end')
            ->setParameters([
                'id' => $id,
                'start' => $startOfMonth,
                'end' => $endOfDate,
            ])
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return PrimePerformance[] Returns an array of PrimePerformance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PrimePerformance
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
