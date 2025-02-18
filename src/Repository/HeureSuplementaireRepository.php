<?php

namespace App\Repository;

use App\Entity\Employe;
use App\Entity\HeureSuplementaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HeureSuplementaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method HeureSuplementaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method HeureSuplementaire[]    findAll()
 * @method HeureSuplementaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeureSuplementaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HeureSuplementaire::class);
    }

    // /**
    //  * @return HeureSuplementaire[] Returns an array of HeureSuplementaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function getTotalHeuresByEmploye(Employe $employe): float
    {
        return (float) $this->createQueryBuilder('h')
            ->select('SUM(h.duree * h.tauxHoraire)')
            ->where('h.employe = :employe')
            ->setParameter('employe', $employe)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /*
    public function findOneBySomeField($value): ?HeureSuplementaire
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
