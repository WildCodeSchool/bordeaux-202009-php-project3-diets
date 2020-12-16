<?php

namespace App\Repository;

use App\Entity\RegisteredEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RegisteredEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegisteredEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegisteredEvent[]    findAll()
 * @method RegisteredEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegisteredEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegisteredEvent::class);
    }

    // /**
    //  * @return RegisteredEvent[] Returns an array of RegisteredEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RegisteredEvent
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
