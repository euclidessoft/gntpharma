<?php

namespace App\Repository;

use App\Entity\TypeDecision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeDecision|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeDecision|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeDecision[]    findAll()
 * @method TypeDecision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeDecisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeDecision::class);
    }

    // /**
    //  * @return TypeDecision[] Returns an array of TypeDecision objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeDecision
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
