<?php

namespace App\Good\Command;

use App\Shared\CQRS\CommandBus\CommandInterface;

final class UpdateGoodPriceCommand implements CommandInterface
{
    public string $aggregateUuid;
    public float $priceInUsd;

    public function __construct(string $aggregateUuid, float $priceInUsd)
    {
        $this->aggregateUuid = $aggregateUuid;
        $this->priceInUsd = $priceInUsd;
    }
}