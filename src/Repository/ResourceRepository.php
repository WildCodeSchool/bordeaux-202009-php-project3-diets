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
        $queryBuiler = $this->createQueryBuilder('r')
            ->where('r.pathology LIKE :name')
            ->orWhere('r.resourceFormat LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->getQuery();

        return $queryBuiler->getResult();
    }
}
