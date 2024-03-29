<?php

namespace App\Good\Event;

use App\Money\DollarMoney;
use Nlf\Component\Event\Aggregate\Event\EventFactoryInterface;
use Nlf\Component\Event\Aggregate\Event\EventInterface;
use Nlf\Component\Event\Aggregate\Event\EventProps;

final class GoodPriceUpdatedEventFactory implements EventFactoryInterface
{
    public function create(
        string $eventName,
        EventProps $props,
        array $payload
    ): EventInterface {
        return new GoodPriceUpdatedEvent(
            $props,
            new DollarMoney($payload['priceInUsd']),
        );
    }
}