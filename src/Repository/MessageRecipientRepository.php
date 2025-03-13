<?php

namespace App\Repository;

use App\Entity\MessageRecipient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MessageRecipient|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageRecipient|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageRecipient[]    findAll()
 * @method MessageRecipient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRecipientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageRecipient::class);
    }

    // /**
    //  * @return MessageRecipient[] Returns an array of MessageRecipient objects
    //  */

    public function trash($user)
    {
        return $this->createQueryBuilder('m')
            ->where('m.deletedAt IS NOT NULL')
            ->AndWhere('m.recipient = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?MessageRecipient
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
