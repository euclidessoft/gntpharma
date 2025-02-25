<?php

namespace App\Repository;

use App\Entity\Paie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Paie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paie[]    findAll()
 * @method Paie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paie::class);
    }

    // /**
    //  * @return Paie[] Returns an array of Paie objects
    //  */

    public function findByDate($id, \DateTime $startOfMonth, \DateTime $endOfDate)
    {
        return $this->createQueryBuilder('p')
            ->where('p.employe = :id')
            ->andWhere('p.mois BETWEEN :start AND :end')
            ->setParameters([
                'id' => $id,
                'start' => $startOfMonth,
                'end' => $endOfDate,
            ])
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Paie
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
