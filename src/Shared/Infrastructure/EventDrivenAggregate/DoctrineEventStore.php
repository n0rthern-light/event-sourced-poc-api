<?php

namespace App\Shared\Infrastructure\EventDrivenAggregate;

use Nlf\Component\Event\Aggregate\AbstractAggregateRoot;
use Nlf\Component\Event\Aggregate\AggregateUuidInterface;
use Nlf\Component\Event\Aggregate\EventCollection;
use Nlf\Component\Event\Aggregate\EventCollectionInterface;
use Nlf\Component\Event\Aggregate\EventFactoryInterface;
use Nlf\Component\Event\Aggregate\EventStoreInterface;

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

    public function storeEvents(AbstractAggregateRoot $aggregate, EventCollectionInterface $events): void
    {
        foreach($events as $event) {
            $doctrineEvent = new Event(
                null,
                $aggregate->getUuid(),
                $event->getEventName(),
                $event->getJsonPayload(),
                $event->getCreatedAt()
            );

            $this->repository->add($doctrineEvent);
        }

        $this->repository->save();
    }

    public function getEvents(AggregateUuidInterface $uuid): EventCollectionInterface
    {
        /** @var Event[] $doctrineEvents */
        $doctrineEvents = $this->repository->findBy([
            'aggregateUuid' => (string)$uuid
        ]);

        $events = [];
        foreach($doctrineEvents as $doctrineEvent) {
            $events[] = $this->eventFactory->create(
                $doctrineEvent->getEventName(),
                new StringUuid((string)$doctrineEvent->getAggregateUuid()),
                $doctrineEvent->getCreatedAt(),
                $doctrineEvent->getPayload()
            );
        }

        return new EventCollection(...$events);
    }
}