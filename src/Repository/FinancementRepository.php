<?php

namespace App\Repository;

use App\Entity\Financement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Financement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Financement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Financement[]    findAll()
 * @method Financement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FinancementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Financement::class);
    }

    // /**
    //  * @return Financement[] Returns an array of Financement objects
    //  */

    public function financementEspece()
    {
        return $this->createQueryBuilder('f')
            ->Where('f.Type = :val')
            ->setParameter('val', 'Espece')
        ;
    }

    public function financementBancaire()
    {
        return $this->createQueryBuilder('f')
            ->Where('f.Type = :val')
            ->setParameter('val', 'Banque')
        ;
    }
  

    /*
    public function findOneBySomeField($value): ?Financement
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
