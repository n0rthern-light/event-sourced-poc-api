<?php

namespace App\Shared\Infrastructure\EventDrivenAggregate;

use Nlf\Component\Event\Aggregate\Shared\UuidInterface;
use Nlf\Component\Event\Aggregate\Snapshot\SnapshotFactoryInterface;
use Nlf\Component\Event\Aggregate\Snapshot\SnapshotInterface;
use Nlf\Component\Event\Aggregate\Snapshot\SnapshotProps;
use Nlf\Component\Event\Aggregate\Snapshot\SnapshotStoreInterface;

final class DoctrineSnapshotStore implements SnapshotStoreInterface
{
    private SnapshotRepository $repository;
    private SnapshotFactoryInterface $factory;

    public function __construct(
        SnapshotRepository $repository,
        SnapshotFactoryInterface $factory
    ) {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    public function getLastSnapshotOfAggregate(UuidInterface $aggregateUuid): ?SnapshotInterface
    {
        $snapshot = $this->repository
            ->findLastOfAggregateUuid($aggregateUuid);

        if (!$snapshot) {
            return null;
        }

        return $this->factory->create(
            new SnapshotProps(
                $snapshot->getLastTakenIntoAccountEventId(),
                $snapshot->getAggregateUuid(),
                $snapshot->getAggregateName(),
                $snapshot->getAggregateState()
            )
        );
    }

    public function store(SnapshotInterface $snapshot): void
    {
        $prev = $this->repository->findLastOfAggregateUuid($snapshot->getAggregateUuid());

        if ($prev) {
            $prev->setLastTakenIntoAccountEventId($snapshot->getLastCoveredEventId());
            $prev->setAggregateName($snapshot->getAggregateName());
            $prev->setAggregateState($snapshot->getAggregateState());
            $prev->setCreatedAt(new \DateTime());
        }

        $this->repository->add(
            $prev ?:
            new Snapshot(
                null,
                $snapshot->getLastCoveredEventId(),
                $snapshot->getAggregateUuid(),
                $snapshot->getAggregateName(),
                $snapshot->getAggregateState(),
                new \DateTime()
            ),
            true
        );
    }
}
