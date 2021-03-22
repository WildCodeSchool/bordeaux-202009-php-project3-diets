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
    public function searchLikeName(string $name)
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->where('r.name LIKE :name')
            ->orWhere('r.description LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->getQuery();
        return $queryBuilder->getResult();
    }

    public function searchByPathologyFormatAndLikeName(array $pathology, string $format, string $name)
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->leftJoin('r.pathology', 'p')
            ->leftJoin('r.resourceFormat', 'f')
            ->where('r.name LIKE :name')
            ->orWhere('r.description LIKE :name')
            ->andWhere('p.identifier IN (:pathology)')
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

    public function searchByPathology(array $pathology)
    {
            $queryBuilder = $this->createQueryBuilder('r')
                ->leftJoin('r.pathology', 'p')
                ->andWhere('p.identifier IN (:pathology)')
                ->setParameter('pathology', $pathology)
                ->getQuery();
        return $queryBuilder->getResult();
    }

    public function searchByPathologyAndFormat(array $pathology, string $format)
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->leftJoin('r.pathology', 'p')
            ->leftJoin('r.resourceFormat', 'f')
            ->andWhere('p.identifier IN (:pathology)')
            ->andWhere('f.identifier = :format')
            ->setParameter('pathology', $pathology)
            ->setParameter('format', $format)
            ->getQuery();
        return $queryBuilder->getResult();
    }

    public function searchByPathologyAndLikeName(array $pathology, string $name)
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->leftJoin('r.pathology', 'p')
            ->where('r.name LIKE :name')
            ->orWhere('r.description LIKE :name')
            ->andWhere('p.identifier IN (:pathology)')
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
            ->orWhere('r.description LIKE :name')
            ->andWhere('f.identifier = :format')
            ->setParameter('name', '%' . $name . '%')
            ->setParameter('format', $format)
            ->getQuery();
        return $queryBuilder->getResult();
    }

    public function verifyResourceFormatUsed(array $resourceFormats)
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->leftJoin('r.resourceFormat', 'f')
            ->where('r.resourceFormat IN (:resourceFormats)')
            ->setParameter('resourceFormats', $resourceFormats)
            ->getQuery();
        return $queryBuilder->getResult();
    }

    public function nextVisio()
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->where('r.date_start >= :datecourant OR r.date_start IS NULL')
            ->setParameter('datecourant', new \DateTime('now'))
            ->orderBy('r.date_start', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }
}
