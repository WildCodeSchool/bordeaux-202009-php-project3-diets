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
    public function searchLikeName(string $name) {
        $queryBuilder = $this->createQueryBuilder('r')
            ->where('r.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function searchByPathologyFormatAndLikeName(string $pathology, string $format, string $name)
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->leftJoin('r.pathology', 'p')
            ->leftJoin('r.resourceFormat', 'f')
            ->where('r.name LIKE :name')
            ->andWhere('p.identifier = :pathology')
            ->andWhere('f.identifier = :format')
            ->setParameter('name', '%' . $name . '%')
            ->setParameter('pathology', $pathology)
            ->setParameter('format', $format)
            ->getQuery();
        return $queryBuilder->getResult();
    }

    public function searchByFormat(string $format)
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->leftJoin('r.resourceFormat', 'f')
            ->andWhere('f.identifier = :format')
            ->setParameter('format', $format)
            ->getQuery();
        return $queryBuilder->getResult();
    }

    public function searchByPathology(string $pathology)
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->leftJoin('r.pathology', 'p')
            ->andWhere('p.identifier = :pathology')
            ->setParameter('pathology', $pathology)
            ->getQuery();
        return $queryBuilder->getResult();
    }

    public function searchByPathologyAndFormat(string $pathology, string $format)
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->leftJoin('r.pathology', 'p')
            ->leftJoin('r.resourceFormat', 'f')
            ->andWhere('p.identifier = :pathology')
            ->andWhere('f.identifier = :format')
            ->setParameter('pathology', $pathology)
            ->setParameter('format', $format)
            ->getQuery();
        return $queryBuilder->getResult();
    }

    public function searchByPathologyAndLikeName(string $pathology, string $name)
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->leftJoin('r.pathology', 'p')
            ->where('r.name LIKE :name')
            ->andWhere('p.identifier = :pathology')
            ->setParameter('name', '%' . $name . '%')
            ->setParameter('pathology', $pathology)
            ->getQuery();
        return $queryBuilder->getResult();
    }

    public function searchByFormatAndLikeName(string $format, string $name)
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->leftJoin('r.resourceFormat', 'f')
            ->where('r.name LIKE :name')
            ->andWhere('f.identifier = :format')
            ->setParameter('name', '%' . $name . '%')
            ->setParameter('format', $format)
            ->getQuery();
        return $queryBuilder->getResult();
    }


}
