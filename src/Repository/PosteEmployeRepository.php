<?php

namespace App\Repository;

use App\Entity\PosteEmploye;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PosteEmploye|null find($id, $lockMode = null, $lockVersion = null)
 * @method PosteEmploye|null findOneBy(array $criteria, array $orderBy = null)
 * @method PosteEmploye[]    findAll()
 * @method PosteEmploye[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PosteEmployeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PosteEmploye::class);
    }

    // /**
    //  * @return PosteEmploye[] Returns an array of PosteEmploye objects
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
    public function findOneBySomeField($value): ?PosteEmploye
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
