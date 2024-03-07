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

    public function nouveaute()
    {
        $date = new \Datetime();
        date_sub($date,date_interval_create_from_date_string("30 days"));
       $creation = date_format($date,"Y-m-d");



        $query = $this->createQueryBuilder('a')
            ->Where('a.creation > :date')
            ->setParameter('date', $creation)
            ->getQuery();
        return $query->getResult();
    }


    public function surveil()
    {
        $query = $this->createQueryBuilder('a')
            ->Where('a.stock < :seuil')
            ->setParameter('seuil', 100)
            ->AndWhere('a.stock > :stock')
            ->setParameter('stock', 0)
            ->getQuery();
        return $query->getResult();
    }




}
