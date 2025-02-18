<?php

namespace App\Repository;

use App\Entity\Employe;
use App\Entity\Retenue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Retenue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Retenue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Retenue[]    findAll()
 * @method Retenue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RetenueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Retenue::class);
    }

    // /**
    //  * @return Retenue[] Returns an array of Retenue objects
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
    public function getTotalRetenuesByEmploye(Employe $employe): float
    {
        return (float) $this->createQueryBuilder('d')
            ->select('SUM(d.montant)')
            ->where('d.employe = :employe')
            ->setParameter('employe', $employe)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /*
    public function findOneBySomeField($value): ?Retenue
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
