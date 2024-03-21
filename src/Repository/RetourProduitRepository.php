<?php

namespace App\Repository;

use App\Entity\RetourProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RetourProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method RetourProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method RetourProduit[]    findAll()
 * @method RetourProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RetourProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RetourProduit::class);
    }

    public function retour_client($client)
    {
        return $this->createQueryBuilder('l')
            ->Join('l.commande', 's', 'WITH', 's.user = :id')
            ->setParameter('id', $client)
            ->addSelect('s')
            ->andWhere('l.rembourser = :rembourser')
            ->setParameter('rembourser', false)
            ->andWhere('l.avoir = :avoir')
            ->setParameter('avoir', false)
            ->andWhere('l.valider = :valider')
            ->setParameter('valider', true)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return RetourProduit[] Returns an array of RetourProduit objects
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
    public function findOneBySomeField($value): ?RetourProduit
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
