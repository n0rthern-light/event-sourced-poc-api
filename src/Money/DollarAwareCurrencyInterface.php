<?php

namespace App\Money;

interface DollarAwareCurrencyInterface extends CurrencyInterface
{
    public function toDollarRatio(): float;
}