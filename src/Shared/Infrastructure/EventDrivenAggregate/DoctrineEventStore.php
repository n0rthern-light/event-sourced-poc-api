<?php

namespace App\Shared\Infrastructure\EventDrivenAggregate;

use Nlf\Component\Event\Aggregate\Aggregate\AbstractAggregateRoot;
use Nlf\Component\Event\Aggregate\Event\EventCollection;
use Nlf\Component\Event\Aggregate\Event\EventCollectionInterface;
use Nlf\Component\Event\Aggregate\Event\EventFactoryInterface;
use Nlf\Component\Event\Aggregate\Event\EventInterface;
use Nlf\Component\Event\Aggregate\Event\EventProps;
use Nlf\Component\Event\Aggregate\Event\EventStoreInterface;
use Nlf\Component\Event\Aggregate\Event\EventStoreQueryCriteria;
use Nlf\Component\Event\Aggregate\Shared\UuidInterface;

final class DoctrineEventStore implements EventStoreInterface
{
    private EventRepository $repository;
    private EventFactoryInterface $eventFactory;

    /**
     * @param EventRepository $repository
     */
    public function __construct(
        EventRepository $repository,
        EventFactoryInterface $eventFactory
    ) {
        $this->repository = $repository;
        $this->eventFactory = $eventFactory;
    }

    public function getAggregateLastEventId(UuidInterface $aggregateUuid): ?int
    {
        return $this->repository->lastIndexOfAggregate($aggregateUuid);
    }

    public function storeEvents(AbstractAggregateRoot $aggregate, EventCollectionInterface $events): void
    {
        /** @var EventInterface $event */
        foreach($events as $event) {
            $doctrineEvent = new Event(
                null,
                $event->getEventUuid(),
                $aggregate->getUuid(),
                $event->getEventName(),
                $event->getJsonPayload(),
                $event->getCreatedAt()
            );

            $this->repository->add($doctrineEvent);
        }

        $this->repository->save();
    }

    public function getEvents(UuidInterface $uuid): EventCollectionInterface
    {
        /** @var Event[] $doctrineEvents */
        $doctrineEvents = $this->repository->findBy([
            'aggregateUuid' => (string)$uuid
        ]);

        return $this->transformDoctrineEventsToAggregateEvents($doctrineEvents);
    }

    public function findEventsByCriteria(EventStoreQueryCriteria $criteria): EventCollectionInterface
    {
        /** @var Event[] $doctrineEvents */
        $doctrineEvents = $this->repository->findAllByCriteria($criteria);

        return $this->transformDoctrineEventsToAggregateEvents($doctrineEvents);
    }

    /**
     * @param Event[] $doctrineEvents
     */
    private function transformDoctrineEventsToAggregateEvents(array $doctrineEvents): EventCollectionInterface
    {
        $events = [];
        foreach($doctrineEvents as $doctrineEvent) {
            $events[] = $this->eventFactory->create(
                $doctrineEvent->getEventName(),
                new EventProps(
                    $doctrineEvent->getEventUuid(),
                    $doctrineEvent->getAggregateUuid(),
                    $doctrineEvent->getCreatedAt(),
                ),
                $doctrineEvent->getPayload()
            );
        }

        return new EventCollection(...$events);
    }
}