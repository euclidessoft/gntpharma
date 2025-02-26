<?php

namespace App\Repository;

use App\Entity\MessageReply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MessageReply|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageReply|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageReply[]    findAll()
 * @method MessageReply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageReplyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageReply::class);
    }

    // /**
    //  * @return MessageReply[] Returns an array of MessageReply objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MessageReply
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
