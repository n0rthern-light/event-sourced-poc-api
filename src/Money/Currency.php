<?php

namespace App\Money;

class Currency implements CurrencyInterface
{
    private string $code;

    public function __construct(string $code)
    {
        $this->code = \strtoupper($code);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function isSame(CurrencyInterface $other): bool
    {
        return $this->code === $other->getCode();
    }

    public function isOfCode(string $currencyCode): bool
    {
        return $this->code === \strtoupper($currencyCode);
    }
}
