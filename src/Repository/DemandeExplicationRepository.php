<?php

namespace App\Repository;

use App\Entity\DemandeExplication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DemandeExplication|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeExplication|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeExplication[]    findAll()
 * @method DemandeExplication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeExplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeExplication::class);
    }

    // /**
    //  * @return DemandeExplication[] Returns an array of DemandeExplication objects
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

    public function findByEmploye($employe)
    {
        return $this->createQueryBuilder('d')
            ->join('d.employe', 'e')
            ->where('e = :employe')
            ->setParameter('employe', $employe)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?DemandeExplication
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
