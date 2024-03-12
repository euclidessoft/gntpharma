<?php

namespace App\Repository;

use App\Entity\Livrer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Livrer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livrer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livrer[]    findAll()
 * @method Livrer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivrerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livrer::class);
    }


    public function historique()
    {
        return $this->createQueryBuilder('l')
            ->groupBy('l.commande')
            ->orderBy('l.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function historique_client($client)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.id = :id')
            ->setParameter('id', $client)
            ->groupBy('l.commande')
            ->orderBy('l.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function historique_livreur($livreur)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.livreur = :id')
            ->setParameter('id', $livreur)
            ->andWhere('l.livrer = :livrer')
            ->setParameter('livrer', true)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Livrer
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
