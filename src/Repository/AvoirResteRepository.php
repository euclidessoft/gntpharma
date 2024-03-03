<?php

namespace App\Repository;

use App\Entity\AvoirReste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AvoirReste|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvoirReste|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvoirReste[]    findAll()
 * @method AvoirReste[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvoirResteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvoirReste::class);
    }

    // /**
    //  * @return AvoirReste[] Returns an array of AvoirReste objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AvoirReste
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
