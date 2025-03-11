<?php

namespace App\Repository;

use App\Entity\Absence;
use App\Entity\Employe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Absence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Absence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Absence[]    findAll()
 * @method Absence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbsenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Absence::class);
    }

    // /**
    //  * @return Absence[] Returns an array of Absence objects
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

    public function findByEmployeAndDates(Employe $employe, array $dates)
    {
        return $this->createQueryBuilder('a')
            ->where('a.employe = :employe')
            ->andWhere('a.dateAbsence IN (:dates)')
            ->setParameter('employe', $employe)
            ->setParameter('dates', $dates)
            ->getQuery()
            ->getResult();
    }

    public function findAbsenceAll($employe): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.employe = :employe')
            ->andWhere('(a.status != :status OR a.justifier != :justifier)')
            ->setParameter('employe', $employe)
            ->setParameter('status', '0')
            ->setParameter('justifier', '0')
            ->getQuery()
            ->getResult();
    }


    public function findAbsenceAttente($employe): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.employe = :employe')
            ->andWhere('a.status = :status')
            ->andWhere('a.justifier = :justifier')
            ->setParameter('employe', $employe)
            ->setParameter('status', '0')
            ->setParameter('justifier', '0')
            ->getQuery()
            ->getResult();
    }

    public function findAbsenceRefuser($employe): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.employe = :employe')
            ->andWhere('a.status = :status')
            ->andWhere('a.justifier = :justifier')
            ->setParameter('employe', $employe)
            ->setParameter('status', '0')
            ->setParameter('justifier', '1')
            ->getQuery()
            ->getResult();
    }

    public function findAbsenceAccepeter($employe): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.employe = :employe')
            ->andWhere('a.status = :status')
            ->andWhere('a.justifier = :justifier')
            ->setParameter('employe', $employe)
            ->setParameter('status', '1')
            ->setParameter('justifier', '1')
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Absence
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
