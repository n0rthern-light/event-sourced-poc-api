<?php

namespace App\Good\Command;

use App\Good\Event\GoodCreatedEvent;
use App\Good\Event\GoodPriceUpdatedEvent;
use App\Money\DollarMoney;
use Nlf\Component\Event\Aggregate\Aggregate\AggregateEventsHandler;
use Nlf\Component\Event\Aggregate\Event\EventInterface;
use Nlf\Component\Event\Aggregate\Event\EventStoreInterface;
use Nlf\Component\Event\Aggregate\Event\EventStoreQueryCriteria;
use Nlf\Component\Event\Aggregate\Shared\UuidInterface;
use Nlf\Component\Event\Aggregate\Snapshot\SnapshotStoreInterface;

final class EsGoodAggregateRepository implements GoodAggregateRepositoryInterface
{
    private EventStoreInterface $eventStore;
    private SnapshotStoreInterface $snapshotStore;
    private AggregateEventsHandler $aggregateEventsHandler;

    public function __construct(
        EventStoreInterface $eventStore,
        SnapshotStoreInterface $snapshotStore,
        AggregateEventsHandler $aggregateEventsHandler
    ) {
        $this->eventStore = $eventStore;
        $this->snapshotStore = $snapshotStore;
        $this->aggregateEventsHandler = $aggregateEventsHandler;
    }

    public function get(UuidInterface $uuid): ?GoodAggregate
    {
        $events = $this->eventStore->getEvents($uuid);
        $lastAndUnique = $events->lastAndUnique();

        $aggregateUuid = null;
        $goodName = null;
        $goodCode = null;
        $good = null;

        /** @var EventInterface $event */
        foreach($lastAndUnique as $event) {
            if ($event->getEventName() === 'GoodCreatedEvent') {
                /** @var GoodCreatedEvent $event */
                $aggregateUuid = $event->getAggregateUuid();
                $goodName = $event->getGoodName();
                $goodCode = $event->getGoodCode();
            }

            if ($event->getEventName() === 'GoodPriceUpdatedEvent') {
                /** @var GoodPriceUpdatedEvent $event */
                $good = new GoodAggregate(
                    $aggregateUuid,
                    $goodCode,
                    $goodName,
                    new DollarMoney($event->getPriceInUsd())
                );
            }
        }

        return $good;
    }

    public function findOneByCode(string $goodCode): ?GoodAggregate
    {
        $events = $this->eventStore->findEventsByCriteria(
            new EventStoreQueryCriteria(
                null,
                null,
                'GoodCreatedEvent',
                ['goodCode' => $goodCode]
            )
        );

        if ($events->isEmpty()) {
            return null;
        }

        /** @var GoodCreatedEvent $creationEvent */
        $creationEvent = $events->last();
        return $this->get($creationEvent->getAggregateUuid());
    }

    public function save(GoodAggregate $goodAggregate): void
    {
        $this->aggregateEventsHandler->commitAggregateEvents($goodAggregate);
    }
}
