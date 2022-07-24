<?php

namespace App\Good\Command;

use App\Shared\CQRS\CommandBus\CommandInterface;

final class UpdateGoodCommand implements CommandInterface
{
    public string $goodCode;
    public float $priceInUsd;

    public function __construct(string $goodCode, float $priceInUsd)
    {
        $this->goodCode = $goodCode;
        $this->priceInUsd = $priceInUsd;
    }
}
