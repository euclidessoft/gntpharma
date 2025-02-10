<?php

namespace App\Repository;

use App\Entity\ReponseExplication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReponseExplication|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseExplication|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseExplication[]    findAll()
 * @method ReponseExplication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseExplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReponseExplication::class);
    }

    // /**
    //  * @return ReponseExplication[] Returns an array of ReponseExplication objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReponseExplication
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
