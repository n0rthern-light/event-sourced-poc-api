<?php

namespace App\Money;

final class DollarMoney extends Money implements ExchangeableMoneyInterface
{
    public function __construct(float $amount)
    {
        parent::__construct($amount, new DollarCurrency());
    }

    public function getAmountInDollar(): float
    {
        return $this->amount;
    }

    public function toDollar(): ExchangeableMoneyInterface
    {
        return $this;
    }

    public function toCurrency(DollarAwareCurrencyInterface $otherCurrency): ExchangeableMoneyInterface
    {
        if ($otherCurrency->isOfCode(DollarCurrency::CURRENCY_CODE)) {
            return $this;
        }

        return new ExchangeableMoney(
            $this->getAmountInDollar() * $otherCurrency->toDollarRatio(),
            $otherCurrency
        );
    }

    public function toMoney(): MoneyInterface
    {
        return new Money($this->amount, new DollarCurrency());
    }
}