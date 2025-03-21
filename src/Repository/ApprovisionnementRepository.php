<?php

namespace App\Repository;

use App\Entity\Approvisionnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Approvisionnement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Approvisionnement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Approvisionnement[]    findAll()
 * @method Approvisionnement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApprovisionnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Approvisionnement::class);
    }

    public function arrivage(array $appro)
    {
        $query = $this->createQueryBuilder('a');
        return $query->where($query->expr()->in('a.approvisionner', $appro))
            ->groupBy('a.produit')
            ->getQuery()
            ->getResult();
    }

    public function payer()
    {
         return $query = $this->createQueryBuilder('a')
            ->andWhere('a.regler = :con')
            ->setParameter('con' , false)
            ->groupBy('a.fournisseur')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Approvisionnement[] Returns an array of Approvisionnement objects
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

    /*
    public function findOneBySomeField($value): ?Approvisionnement
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
