<?php

namespace App\Repository;

use App\Entity\Employe;
use App\Entity\Prime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Prime|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prime|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prime[]    findAll()
 * @method Prime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prime::class);
    }

    // /**
    //  * @return Prime[] Returns an array of Prime objects
    //  */

    public function findByDateRange($id, \DateTime $startOfMonth, \DateTime $endOfDate)
    {
        return $this->createQueryBuilder('p')
            ->where('p.employe = :id')
            ->andWhere('p.createdAt BETWEEN :start AND :end')
            ->setParameters([
                'id' => $id,
                'start' => $startOfMonth,
                'end' => $endOfDate,
            ])
            ->getQuery()
            ->getResult();
    }


    public function getTotalPrimesByEmploye(Employe $employe): float
    {
        return (float)$this->createQueryBuilder('p')
            ->select('SUM(p.montant)')
            ->where('p.employe = :employe')
            ->setParameter('employe', $employe)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /*
    public function findOneBySomeField($value): ?Prime
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
