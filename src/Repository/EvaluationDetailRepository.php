<?php

namespace App\Repository;

use App\Entity\EvaluationDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EvaluationDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method EvaluationDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method EvaluationDetail[]    findAll()
 * @method EvaluationDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvaluationDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvaluationDetail::class);
    }

    // /**
    //  * @return EvaluationDetail[] Returns an array of EvaluationDetail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EvaluationDetail
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
