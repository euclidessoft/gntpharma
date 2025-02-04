<?php

namespace App\Repository;

use App\Entity\EmployeFormation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EmployeFormation|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmployeFormation|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmployeFormation[]    findAll()
 * @method EmployeFormation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeFormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployeFormation::class);
    }

    // /**
    //  * @return EmployeFormation[] Returns an array of EmployeFormation objects
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
    public function findOneBySomeField($value): ?EmployeFormation
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
