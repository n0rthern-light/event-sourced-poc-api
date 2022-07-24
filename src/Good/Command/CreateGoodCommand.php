<?php

namespace App\Good\Command;

use App\Shared\CQRS\CommandBus\CommandInterface;

final class CreateGoodCommand implements CommandInterface
{
    public string $goodName;
    public string $goodCode;
    public float $priceInUsd;

    public function __construct(string $goodName, string $goodCode, float $priceInUsd)
    {
        $this->goodName = $goodName;
        $this->goodCode = $goodCode;
        $this->priceInUsd = $priceInUsd;
    }
}
