<?php

namespace App\Good\Event;

use App\Money\DollarMoney;
use DateTimeInterface;
use Nlf\Component\Event\Aggregate\AbstractAggregateEvent;
use Nlf\Component\Event\Aggregate\AggregateUuidInterface;

class GoodPriceUpdatedEvent extends AbstractAggregateEvent
{
    private float $priceInUsd;

    public function __construct(
        AggregateUuidInterface $aggregateUuid,
        DollarMoney $price,
        ?DateTimeInterface $createdAt = null
    ) {
        $this->priceInUsd = $price->getAmount();
        parent::__construct($aggregateUuid, $createdAt);
    }

    public function getJsonPayload(): array
    {
        return ['priceInUsd' => $this->priceInUsd];
    }
}