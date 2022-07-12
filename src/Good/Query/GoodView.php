<?php

namespace App\Good\Query;

use App\Money\DollarMoney;
use DateTimeInterface;

class GoodView
{
    private string $code;
    private string $name;
    private DollarMoney $lastPrice;
    private DateTimeInterface $priceUpdatedOn;

    public function __construct(string $code, string $name, DollarMoney $lastPrice, DateTimeInterface $priceUpdatedOn)
    {
        $this->code = $code;
        $this->name = $name;
        $this->lastPrice = $lastPrice;
        $this->priceUpdatedOn = $priceUpdatedOn;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastPrice(): DollarMoney
    {
        return $this->lastPrice;
    }

    public function getPriceUpdatedOn(): DateTimeInterface
    {
        return $this->priceUpdatedOn;
    }
}