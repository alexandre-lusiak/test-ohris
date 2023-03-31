<?php

namespace App\Repository;

use App\Entity\Artist;
use App\Entity\Event;
use App\Entity\SearchData;
use App\Entity\SearchFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function save(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Event[] Returns an array of Event objects
//     */
   public function findByDate(int $page , int $limit): array
   {
    $qb = $this->createQueryBuilder('e')
            ->orderBy('e.eventDate', 'ASC')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit);
    return $qb->getQuery()->getResult();
       ;
   }

   public function findUpcomingEvents()
   {
       $startDate = new \DateTime();
       $endDate = new \DateTime();
       $endDate->modify('+3 months');

       return $this->createQueryBuilder('e')
           ->where('e.eventDate >= :start_date')
           ->andWhere('e.eventDate <= :end_date')
           ->setParameter('start_date', $startDate)
           ->setParameter('end_date', $endDate)
           ->orderBy('e.eventDate', 'ASC')
           ->getQuery()
           ->getResult();
   }



//    public function search(SearchFilter $filter = null, int $page , int $limit  ) 
// {
//     $qb = $this->createQueryBuilder('e');
//     $qb->orderBy('e.eventDate', 'ASC');
    
//     if ($filter && $filter->getStartDate()) {
//         $qb->andWhere('e.eventDate >= :start_date')
//             ->setParameter('start_date', $filter->getStartDate());
//     } else {
//         $today = new \DateTime();
//         $threeMonthsFromToday = (new \DateTime())->modify('+3 months');
//         $qb->andWhere('e.eventDate >= :today')
//             ->andWhere('e.eventDate <= :three_months_from_today')
//             ->setParameter('today', $today)
//             ->setParameter('three_months_from_today', $threeMonthsFromToday);
//     }

//     if ($filter && $filter->getEndDate()) {
//         $qb->andWhere('e.eventDate <= :end_date')
//             ->setParameter('end_date', $filter->getEndDate());
//     }

//     if ($filter && $filter->getArtist()) {
//         $qb->andWhere('e.artist = :artist')
//             ->setParameter('artist', $filter->getArtist());
//     }

//     if ($filter && $filter->getCity()) {
//         $qb->andWhere('e.city = :city')
//             ->setParameter('city', $filter->getCity());
//     }

//     $qb->setFirstResult(($page * $limit) - $limit);
//     $qb->setMaxResults($limit);
//     return $qb->getQuery()->getResult();
// }

public function getTotalEvent(){
    $qb = $this->createQueryBuilder('e')
    ->select('count(e)') ;
    return $qb->getQuery()->getSingleScalarResult();
}

public function findByArtistAndDate(Artist $artist, \DateTimeInterface $date): array
{
    return $this->createQueryBuilder('e')
        ->where('e.eventDate = :date')
        ->andWhere('e.artist = :artist')
        ->setParameter('date', $date)
        ->setParameter('artist', $artist)
        ->getQuery()
        ->getResult();
}

public function search(SearchFilter $filter = null, int $page, int $limit)
{
    $qb = $this->createQueryBuilder('e');
    $qb->orderBy('e.eventDate', 'ASC');

    $today = new \DateTime('now',new \DateTimeZone('Europe/Paris'));

    $qb->andWhere('e.eventDate >= :today')
        ->setParameter('today', $today);

    if ($filter && $filter->getStartDate()) {
        $qb->andWhere('e.eventDate >= :start_date')
            ->setParameter('start_date', $filter->getStartDate());
    } else {
        $threeMonthsFromToday = (new \DateTime())->modify('+3 months');
        $qb->andWhere('e.eventDate <= :three_months_from_today')
            ->setParameter('three_months_from_today', $threeMonthsFromToday);
    }

    if ($filter && $filter->getEndDate()) {
        $qb->andWhere('e.eventDate <= :end_date')
            ->setParameter('end_date', $filter->getEndDate());
    }

    if ($filter && $filter->getArtist()) {
        $qb->andWhere('e.artist = :artist')
            ->setParameter('artist', $filter->getArtist());
    }

    if ($filter && $filter->getCity()) {
        $qb->andWhere('e.city = :city')
            ->setParameter('city', $filter->getCity());
    }

    $qb->setFirstResult(($page * $limit) - $limit);
    $qb->setMaxResults($limit);
    return $qb->getQuery()->getResult();
}
}

