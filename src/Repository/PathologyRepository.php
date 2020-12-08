<?php

namespace App\Repository;

use App\Entity\Pathology;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pathology|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pathology|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pathology[]    findAll()
 * @method Pathology[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PathologyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pathology::class);
    }

    // /**
    //  * @return Pathology[] Returns an array of Pathology objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pathology
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
