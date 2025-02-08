<?php

namespace App\Repository;

use App\Entity\DemandeExplicationEmploye;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DemandeExplicationEmploye|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeExplicationEmploye|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeExplicationEmploye[]    findAll()
 * @method DemandeExplicationEmploye[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeExplicationEmployeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeExplicationEmploye::class);
    }

    // /**
    //  * @return DemandeExplicationEmploye[] Returns an array of DemandeExplicationEmploye objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DemandeExplicationEmploye
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
