<?php

namespace App\Money;

interface ExchangeableMoneyInterface extends MoneyInterface
{
    public function getAmountInDollar(): float;
    public function toDollar(): ExchangeableMoneyInterface;
    public function toCurrency(DollarAwareCurrencyInterface $otherCurrency): ExchangeableMoneyInterface;
    public function toMoney(): MoneyInterface;
}