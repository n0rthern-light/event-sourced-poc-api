<?php

namespace App\Money;

interface MoneyInterface
{
    public function getAmount(): float;
    public function getCurrency(): CurrencyInterface;
    public function hasCurrency(CurrencyInterface $currency): bool;
    public function hasCurrencyCode(string $currencyCode): bool;
    public function add(MoneyInterface $other): MoneyInterface;
    public function toExchangeableMoney(DollarAwareCurrencyInterface $dollarAwareCurrency): ExchangeableMoneyInterface;
}