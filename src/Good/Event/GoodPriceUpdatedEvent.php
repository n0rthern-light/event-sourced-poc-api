<?php

namespace App\Good\Event;

use App\Money\DollarMoney;
use Nlf\Component\Event\Aggregate\Event\AbstractEvent;
use Nlf\Component\Event\Aggregate\Event\EventProps;

class GoodPriceUpdatedEvent extends AbstractEvent
{
    private float $priceInUsd;

    public function __construct(
        EventProps $props,
        DollarMoney $price,
    ) {
        $this->priceInUsd = $price->getAmount();
        parent::__construct($props);
    }

    public function getJsonPayload(): array
    {
        return ['priceInUsd' => $this->priceInUsd];
    }

    public function getPriceInUsd(): float
    {
        return $this->priceInUsd;
    }
}