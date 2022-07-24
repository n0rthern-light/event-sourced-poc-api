<?php

namespace App\Good\Command;

use App\Good\Event\GoodCreatedEvent;
use App\Good\Event\GoodPriceUpdatedEvent;
use App\Money\DollarMoney;
use Nlf\Component\Event\Aggregate\Aggregate\AbstractAggregateRoot;
use Nlf\Component\Event\Aggregate\Event\EventProps;
use Nlf\Component\Event\Aggregate\Shared\UuidInterface;

class GoodAggregate extends AbstractAggregateRoot
{
    private string $goodCode;
    private string $goodName;
    private DollarMoney $price;

    public function __construct(
        ?UuidInterface $uuid,
        string $goodCode,
        string $goodName,
        DollarMoney $price
    ) {
        parent::__construct($uuid);

        $this->goodCode = $goodCode;
        $this->goodName = $goodName;
        $this->price = $price;

        $this->whenFresh(function() {
            $this->onFirstInstance();
        });
    }

    public function updatePrice(DollarMoney $price): void
    {
        if ($this->price->getAmount() === $price->getAmount()) {
            return;
        }

        $this->price = $price;

        $this->pushEvent(
            new GoodPriceUpdatedEvent(
                new EventProps(null, $this->uuid, null),
                $price
            )
        );
    }

    private function onFirstInstance(): void
    {
        $this->pushEvent(
            new GoodCreatedEvent(
                new EventProps(null, $this->uuid, null),
                $this->goodCode,
                $this->goodName
            )
        );

        $this->pushEvent(
            new GoodPriceUpdatedEvent(
                new EventProps(null, $this->uuid, null),
                $this->price
            )
        );
    }

    public function getGoodCode(): string
    {
        return $this->goodCode;
    }

    public function getGoodName(): string
    {
        return $this->goodName;
    }

    public function getPrice(): DollarMoney
    {
        return $this->price;
    }

    public function getJsonState(): array
    {
        return [
            'goodCode' => $this->goodCode,
            'goodName' => $this->goodName,
            'priceInUsd' => $this->price->getAmount(),
        ];
    }
}