<?php

namespace App\Good\Query;

use App\Money\MoneyInterface;
use DateTimeImmutable;

class GoodDto
{
    public string $code;
    public string $name;
    public MoneyInterface $lastPrice;
    public DateTimeImmutable $priceUpdatedOn;

    public function __construct(
        string $code,
        string $name,
        MoneyInterface $lastPrice,
        DateTimeImmutable $priceUpdatedOn
    ) {
        $this->code = $code;
        $this->name = $name;
        $this->lastPrice = $lastPrice;
        $this->priceUpdatedOn = $priceUpdatedOn;
    }
}