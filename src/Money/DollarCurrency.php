<?php

namespace App\Money;

final class DollarCurrency implements DollarAwareCurrencyInterface
{
    public const CURRENCY_CODE = 'USD';

    private Currency $inner;

    public function __construct()
    {
        $this->inner = new Currency(self::CURRENCY_CODE);
    }

    public function getCode(): string
    {
        return $this->inner->getCode();
    }

    public function toDollarRatio(): float
    {
        return 1;
    }

    public function isSame(CurrencyInterface $other): bool
    {
        return $this->inner->isSame($other);
    }

    public function isOfCode(string $currencyCode): bool
    {
        return $this->inner->isOfCode($currencyCode);
    }
}