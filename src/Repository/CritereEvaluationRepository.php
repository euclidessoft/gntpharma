<?php

namespace App\Repository;

use App\Entity\CritereEvaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CritereEvaluation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CritereEvaluation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CritereEvaluation[]    findAll()
 * @method CritereEvaluation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CritereEvaluationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CritereEvaluation::class);
    }

    // /**
    //  * @return CritereEvaluation[] Returns an array of CritereEvaluation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CritereEvaluation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
