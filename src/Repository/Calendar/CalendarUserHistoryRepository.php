<?php

namespace App\Repository\Calendar;

use App\Entity\Calendar\CalendarUserHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CalendarUserHistory>
 *
 * @method CalendarUserHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method CalendarUserHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method CalendarUserHistory[]    findAll()
 * @method CalendarUserHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendarUserHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CalendarUserHistory::class);
    }

    public function save(CalendarUserHistory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CalendarUserHistory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CalendarUserHistory[] Returns an array of CalendarUserHistory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CalendarDayHistory
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
