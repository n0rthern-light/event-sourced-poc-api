<?php

namespace App\Money;

interface CurrencyInterface
{
    public function getCode(): string;
    public function isSame(CurrencyInterface $other): bool;
    public function isOfCode(string $currencyCode): bool;
}