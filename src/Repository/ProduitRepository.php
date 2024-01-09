<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function noveaute()
    {
//        $date=date_create("2013-03-15");
//        date_sub($date,date_interval_create_from_date_string("40 days"));
//        echo date_format($date,"Y-m-d");

//
//
//        $query = $this->createQueryBuilder('a')
//            ->Where('a.creation < :genre')
//            ->setParameter('genre', date('Y-m-d'))
//            ->andWhere('a.cellule = :cellule')
//            ->setParameter('cellule', $cellule)
//            ->getQuery();
//        return $query->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Produit
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
