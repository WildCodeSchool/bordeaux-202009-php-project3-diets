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

    public function findLikeName(string $name) {
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.identifier LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('p.identifier', 'ASC')
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findResourceByPathologyName(string $name)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->leftJoin('p.resource', 'r')
            ->where('p.name LIKE :name')
            ->setParameter('name', $name)
            ->getQuery();
        return $queryBuilder->getResult();
    }
}
