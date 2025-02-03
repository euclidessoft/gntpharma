<?php

namespace App\Repository;

use App\Entity\ReponseAbsence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReponseAbsence|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseAbsence|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseAbsence[]    findAll()
 * @method ReponseAbsence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseAbsenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReponseAbsence::class);
    }

    // /**
    //  * @return ReponseAbsence[] Returns an array of ReponseAbsence objects
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
    public function findOneBySomeField($value): ?ReponseAbsence
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
