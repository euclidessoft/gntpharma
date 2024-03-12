<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function current($user)
    {
        // On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
        $query = $this ->createQueryBuilder('a')
            ->Where('a.user = :user')
            ->setParameter('user', $user)
            ->AndWhere('a.suivi = :suivi')
            ->setParameter('suivi', true)
        ;
        return $query;
    }

    public function extranet($user)
    {
        // On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
        $query = $this ->createQueryBuilder('a')
            ->Where('a.user = :user')
            ->setParameter('user', $user)
            ->AndWhere('a.suivi = :suivi')
            ->setParameter('suivi', 0)
            ->AndWhere('a.admin != :admin')
            ->setParameter('admin', "")
        ;
        return $query->getQuery()
            ->getResult();
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


    // /**
    //  * @return Commande[] Returns an array of Commande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Commande
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
