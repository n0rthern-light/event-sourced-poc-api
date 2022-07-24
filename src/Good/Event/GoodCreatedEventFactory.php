<?php

namespace App\Good\Event;

use Nlf\Component\Event\Aggregate\Event\EventFactoryInterface;
use Nlf\Component\Event\Aggregate\Event\EventInterface;
use Nlf\Component\Event\Aggregate\Event\EventProps;

final class GoodCreatedEventFactory implements EventFactoryInterface
{
    public function create(
        string $eventName,
        EventProps $props,
        array $payload
    ): EventInterface {
        return new GoodCreatedEvent(
            $props,
            $payload['goodCode'],
            $payload['goodName']
        );
    }
}