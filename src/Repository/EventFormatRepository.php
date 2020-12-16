<?php

namespace App\Repository;

use App\Entity\EventFormat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventFormat|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventFormat|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventFormat[]    findAll()
 * @method EventFormat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventFormatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventFormat::class);
    }

    // /**
    //  * @return EventFormat[] Returns an array of EventFormat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventFormat
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
