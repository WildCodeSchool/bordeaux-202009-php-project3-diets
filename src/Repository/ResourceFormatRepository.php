<?php

namespace App\Repository;

use App\Entity\ResourceFormat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResourceFormat|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResourceFormat|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResourceFormat[]    findAll()
 * @method ResourceFormat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResourceFormatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResourceFormat::class);
    }

    public function findLikeName(string $name) {
        $queryBuiler = $this->createQueryBuilder('r')
            ->where('r.identifier LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('r.identifier', 'ASC')
            ->getQuery();

        return $queryBuiler->getResult();
    }
}
