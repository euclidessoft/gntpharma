<?php

namespace App\Repository;

use App\Entity\Employe;
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
            ->andWhere('p.date BETWEEN :start AND :end')
            ->setParameters([
                'id' => $id,
                'start' => $startOfMonth,
                'end' => $endOfDate,
            ])
            ->getQuery()
            ->getResult();
    }

    public function findPaieCurrentMonth()
    {
        $startDate = new \DateTime('first day of this month 00:00:00');
        $endDate = new \DateTime('last day of this month 23:59:59');

        return $this->createQueryBuilder('p')
            ->where('p.date BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->getQuery()
            ->getResult();
    }

    public function findByFiltrer(?Employe $employe, ?int $mois, ?int $annee): array
    {
        $qb = $this->createQueryBuilder('p');
        if ($employe) {
            $qb->andWhere('p.employe = :employe')
                ->setParameter('employe', $employe);
        }

        
        if ($mois && $annee) {
            // Début et fin du mois
            $startDate = new \DateTime("$annee-$mois-01");
            $endDate = new \DateTime("$annee-$mois-01");
            $endDate->modify('last day of this month')->setTime(23, 59, 59);

            // Filtrage des paiements entre ces deux dates
            $qb->andWhere('p.datepaye BETWEEN :debut AND :fin')
                ->setParameter('debut', $startDate)
                ->setParameter('fin', $endDate);
        }
        
        elseif ($annee) {
            $start = new \DateTime("$annee-01-01");
            $end = new \DateTime("$annee-12-31");

            $qb->andWhere('p.datepaye BETWEEN :start AND :end')
                ->setParameter('start', $start)
                ->setParameter('end', $end);
        }

        return $qb->orderBy('p.datepaye', 'DESC')
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
