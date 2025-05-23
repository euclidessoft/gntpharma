<?php

namespace App\Repository;

use App\Entity\Conges;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Conges|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conges|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conges[]    findAll()
 * @method Conges[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CongesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conges::class);
    }

    // /**
    //  * @return Conges[] Returns an array of Conges objects
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
    public function findDemandesTraitees($employe): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.employe = :employe')
            ->andWhere('c.status IS NULL')
            ->andWhere('c.dateModifier = :modif')
            ->setParameter('employe', $employe)
            ->setParameter('modif', true)
            ->orderBy('c.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findDemandesEnAttente($employe): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.employe = :employe')
            ->andWhere('c.status IN (:statuses)')
            ->setParameter('employe', $employe)
            ->setParameter('statuses', ['0', '3'])
            ->orderBy('c.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findDemandesAccepter($employe): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.employe = :employe')
            ->andWhere('c.status = :status')
            ->setParameter('employe', $employe)
            ->setParameter('status', '1')
            ->orderBy('c.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findDemandesRefuse($employe): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.employe = :employe')
            ->andWhere('c.status = :status')
            ->setParameter('employe', $employe)
            ->setParameter('status', '2')
            ->orderBy('c.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function Traitees(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.status IS NULL')
            ->andWhere('c.dateModifier = :modif')
            ->setParameter('modif', true)
            ->orderBy('c.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function EnAttente(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.status IN (:statuses)')
            ->setParameter('statuses', ['0', '3'])
            ->orderBy('c.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function Accepter(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.status = :status')
            ->setParameter('status', '1')
            ->orderBy('c.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function Refuse(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.status = :status')
            ->setParameter('status', '2')
            ->orderBy('c.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
