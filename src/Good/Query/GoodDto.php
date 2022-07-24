<?php

namespace App\Good\Query;

use JsonSerializable;

class GoodDto implements JsonSerializable
{
    public string $uuid;
    public string $code;
    public string $name;
    public float $lastPrice;
    public string $priceInCurrency;
    public string $priceUpdateTimestamp;

    public function __construct(string $uuid, string $code, string $name, float $lastPrice, string $priceInCurrency, string $priceUpdateTimestamp)
    {
        $this->uuid = $uuid;
        $this->code = $code;
        $this->name = $name;
        $this->lastPrice = $lastPrice;
        $this->priceInCurrency = $priceInCurrency;
        $this->priceUpdateTimestamp = $priceUpdateTimestamp;
    }

    public function __toString(): string
    {
        return $this->uuid . ' | ' . $this->code . ' | ' . $this->name . ' | ' . $this->lastPrice . ' ' . $this->priceInCurrency . ' | ' . $this->priceUpdateTimestamp;
    }

    public function jsonSerialize(): array
    {
        return \get_object_vars($this);
    }
}