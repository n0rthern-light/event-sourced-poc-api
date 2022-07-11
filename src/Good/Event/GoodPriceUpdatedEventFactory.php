<?php

namespace App\Good\Event;

use App\Money\DollarMoney;
use DateTimeInterface;
use Nlf\Component\Event\Aggregate\AggregateEventInterface;
use Nlf\Component\Event\Aggregate\AggregateUuidInterface;
use Nlf\Component\Event\Aggregate\EventFactoryInterface;

final class GoodPriceUpdatedEventFactory implements EventFactoryInterface
{
    public function create(
        string $eventName,
        AggregateUuidInterface $aggregateUuid,
        DateTimeInterface $createdAt,
        array $payload
    ): AggregateEventInterface {
        return new GoodPriceUpdatedEvent(
            $aggregateUuid,
            new DollarMoney($payload['priceInUsd']),
            $createdAt
        );
    }
}