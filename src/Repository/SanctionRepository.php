<?php

namespace App\Repository;

use App\Entity\Sanction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sanction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sanction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sanction[]    findAll()
 * @method Sanction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SanctionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sanction::class);
    }

    public function findByDateRange($id, \DateTime $startOfMonth, \DateTime $endOfDate)
    {
        return $this->createQueryBuilder('p')
            ->where('p.employe = :id')
            ->andWhere('p.createdAt BETWEEN :start AND :end')
            ->setParameters([
                'id' => $id,
                'start' => $startOfMonth,
                'end' => $endOfDate,
            ])
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Sanction[] Returns an array of Sanction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sanction
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
