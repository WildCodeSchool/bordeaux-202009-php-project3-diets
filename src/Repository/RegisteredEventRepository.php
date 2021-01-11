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

    public function findEventsAndOrganizers()
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.isOrganizer = :val')
            ->setParameter('val', '1')
            ->getQuery()
            ->getResult()
        ;
    }

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
