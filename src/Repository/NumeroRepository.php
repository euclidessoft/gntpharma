<?php

namespace App\Repository;

use App\Entity\Numero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Numero|null find($id, $lockMode = null, $lockVersion = null)
 * @method Numero|null findOneBy(array $criteria, array $orderBy = null)
 * @method Numero[]    findAll()
 * @method Numero[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NumeroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Numero::class);
    }

    // /**
    //  * @return Numero[] Returns an array of Numero objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Numero
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
