<?php

namespace App\Repository;

use App\Entity\SecuringPurchases;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SecuringPurchases|null find($id, $lockMode = null, $lockVersion = null)
 * @method SecuringPurchases|null findOneBy(array $criteria, array $orderBy = null)
 * @method SecuringPurchases[]    findAll()
 * @method SecuringPurchases[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecuringPurchasesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SecuringPurchases::class);
    }

    // /**
    //  * @return SecuringPurchases[] Returns an array of SecuringPurchases objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SecuringPurchases
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
