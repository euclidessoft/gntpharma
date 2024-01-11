<?php

namespace App\Repository;

use App\Entity\Approvisionner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Approvisionner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Approvisionner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Approvisionner[]    findAll()
 * @method Approvisionner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApprovisionnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Approvisionner::class);
    }

    // /**
    //  * @return Approvisionner[] Returns an array of Approvisionner objects
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


    public function arrivage()
    {
        $date = new \Datetime();
        date_sub($date,date_interval_create_from_date_string("7 days"));
        $creation = date_format($date,"Y-m-d");



        $query = $this->createQueryBuilder('a')
            ->Where('a.date > :date')
            ->setParameter('date', $creation)
            ->getQuery();
        return $query->getResult();
    }
}
