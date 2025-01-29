<?php

namespace App\Repository;

use App\Entity\CongeAccorder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CongeAccorder|null find($id, $lockMode = null, $lockVersion = null)
 * @method CongeAccorder|null findOneBy(array $criteria, array $orderBy = null)
 * @method CongeAccorder[]    findAll()
 * @method CongeAccorder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CongeAccorderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CongeAccorder::class);
    }

    // /**
    //  * @return CongeAccorder[] Returns an array of CongeAccorder objects
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
    public function findOneBySomeField($value): ?CongeAccorder
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
