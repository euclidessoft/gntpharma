<?php

namespace App\Repository;

use App\Entity\Employe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Employe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employe[]    findAll()
 * @method Employe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employe::class);
    }

    // /**
    //  * @return Employe[] Returns an array of Employe objects
    //  */

    public function findByEmployeWithDocument()
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->innerJoin('e.documents', 'd')  // Correction : joindre la table des documents correctement
            ->groupBy('e.id')
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Employe
    {
        return $this->createQueryBuilder('e')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
