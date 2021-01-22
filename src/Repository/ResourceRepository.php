<?php

namespace App\Repository;

use App\Entity\Resource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Resource|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resource|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resource[]    findAll()
 * @method Resource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Resource::class);
    }
    public function findLikeName(string $name) {
        $queryBuilder = $this->createQueryBuilder('r')
            ->leftJoin('r.pathology', 'p')
            ->leftJoin('r.resourceFormat', 'f')
            ->where('r.name LIKE :name')
            ->orWhere('r.description LIKE :name')
            ->orWhere('p.name LIKE :name')
            ->orWhere('f.format LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->getQuery();

        return $queryBuilder->getResult();
    }
}
