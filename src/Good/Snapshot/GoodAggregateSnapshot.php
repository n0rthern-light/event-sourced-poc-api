<?php

namespace App\Good\Snapshot;

use App\Good\Command\GoodAggregate;
use App\Good\Event\GoodPriceUpdatedEvent;
use App\Money\DollarMoney;
use Nlf\Component\Event\Aggregate\Aggregate\AbstractAggregateRoot;
use Nlf\Component\Event\Aggregate\Event\EventCollectionInterface;
use Nlf\Component\Event\Aggregate\Snapshot\AbstractSnapshot;

final class GoodAggregateSnapshot extends AbstractSnapshot
{
    public function buildAggregate(EventCollectionInterface $outerEvents): AbstractAggregateRoot
    {
        $statePrice = (float)$this->props->getState()['priceInUsd'];

        /** @var GoodPriceUpdatedEvent|null $updatedPrice */
        $updatedPrice = $outerEvents->filterByEventName('GoodPriceUpdatedEvent')->last();

        return new GoodAggregate(
            $this->getAggregateUuid(),
            $this->props->getState()['goodCode'],
            $this->props->getState()['goodName'],
            new DollarMoney($updatedPrice ? $updatedPrice->getPriceInUsd() : $statePrice)
        );
    }
}