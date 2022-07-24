<?php

namespace App\Good\Query;

use DateTimeInterface;

class GoodView
{
    public string $uuid;
    public string $code;
    public string $name;
    public float $lastPriceInUsd;
    public DateTimeInterface $priceUpdatedOn;
}
