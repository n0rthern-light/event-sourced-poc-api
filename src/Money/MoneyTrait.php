<?php

namespace App\Money;

trait MoneyTrait
{
    protected float $amount;
    protected CurrencyInterface $currency;

    public function __construct(float $amount, CurrencyInterface $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): CurrencyInterface
    {
        return $this->currency;
    }

    public function hasCurrency(CurrencyInterface $other): bool
    {
        return $this->currency->isSame($other);
    }

    public function hasCurrencyCode(string $currencyCode): bool
    {
        return $this->currency->isOfCode($currencyCode);
    }
}