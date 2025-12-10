<?php

namespace App\Repository;

use App\Entity\ActivityLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivityLog>
 */
class ActivityLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityLog::class);
    }

    public function findByFilters(?string $username, ?string $action, ?string $dateFrom, ?string $dateTo)
    {
        $qb = $this->createQueryBuilder('log')
            ->orderBy('log.createdAt', 'DESC');

        if ($username) {
            $qb->andWhere('log.username LIKE :username')
                ->setParameter('username', '%' . $username . '%');
        }

        if ($action) {
            $qb->andWhere('log.action = :action')
                ->setParameter('action', $action);
        }

        if ($dateFrom) {
            $qb->andWhere('log.createdAt >= :dateFrom')
                ->setParameter('dateFrom', new \DateTimeImmutable($dateFrom));
        }

        if ($dateTo) {
            $qb->andWhere('log.createdAt <= :dateTo')
                ->setParameter('dateTo', new \DateTimeImmutable($dateTo));
        }

        return $qb->getQuery()->getResult();
    }


    //    /**
    //     * @return ActivityLog[] Returns an array of ActivityLog objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ActivityLog
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
