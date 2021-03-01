<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);



    }

    public function findAll()
    {
        return $this->findBy(['eventIsValidated' => 1]);
    }

    public function findLikeName(string $name)
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->where('e.name LIKE :name')
            ->orWhere('e.description LIKE :name')
            ->andWhere('e.eventIsValidated = 1')
            ->andWhere('e.dateEnd >= :datecourant')
            ->setParameter('name', '%' . $name . '%')
            ->setParameter('datecourant', \DateTime::CreateFromFormat("d/m/Y h:i", date('now')))
            ->orderBy('e.name', 'ASC')
            ->getQuery();
        return $queryBuilder->getResult();
    }

    public function nextEvent()
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->where('e.dateEnd >= :datecourant')
            ->andWhere('e.eventIsValidated = 1')
            ->setParameter('datecourant', \DateTime::CreateFromFormat("d/m/Y h:i", date('now')))
            ->orderBy('e.dateEnd', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    public function nextEventByFour()
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->where('e.dateStart >= :datecourant')
            ->andWhere('e.eventIsValidated = 1')
            ->setParameter('datecourant', \DateTime::CreateFromFormat("d/m/Y h:i", date('now')))
            ->orderBy('e.dateStart', 'DESC')
            ->setMaxResults('4');



        return $queryBuilder->getQuery()->getResult();
    }


    public function verifyEventFormatUsed(array $eventFormats)
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->leftJoin('e.eventFormat', 'f')
            ->where('e.eventFormat IN (:eventFormats)')
            ->setParameter('eventFormats', $eventFormats)
            ->getQuery();
        return $queryBuilder->getResult();
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
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
    public function findOneBySomeField($value): ?Event
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
