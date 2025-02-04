<?php

namespace App\Repository;

use App\Entity\TypeSanction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeSanction|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeSanction|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeSanction[]    findAll()
 * @method TypeSanction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeSanctionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeSanction::class);
    }

    // /**
    //  * @return TypeSanction[] Returns an array of TypeSanction objects
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
    public function findOneBySomeField($value): ?TypeSanction
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
