<?php

namespace App\Shared\Infrastructure\EventDrivenAggregate;

use App\Shared\Infrastructure\Doctrine\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

class SnapshotRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Snapshot::class);
    }

    public function add(Snapshot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Snapshot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLastOfAggregateUuid(string $uuid): ?Snapshot
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.aggregateUuid = :aggregateUuid')
            ->setParameter('aggregateUuid', $uuid)
            ->orderBy('s.lastTakenIntoAccountEventId', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
