<?php

namespace App\Good\Command;

use App\Good\Event\GoodPriceUpdatedEvent;
use App\Money\DollarMoney;
use Nlf\Component\Event\Aggregate\AbstractAggregateRoot;
use Nlf\Component\Event\Aggregate\AggregateUuidInterface;

class GoodPriceAggregate extends AbstractAggregateRoot
{
    public function __construct(AggregateUuidInterface $uuid)
    {
        parent::__construct($uuid);
    }

    public function updatePrice(DollarMoney $price): void
    {
        $this->pushEvent(new GoodPriceUpdatedEvent($this->uuid, $price));
    }
}