<?php

namespace App\Money;

class Money implements MoneyInterface
{
    use MoneyTrait;

    public function add(MoneyInterface $other): MoneyInterface
    {
        if (!$this->hasCurrency($other->getCurrency())) {
            throw new \InvalidArgumentException('$other should be the same currency in order to add!');
        }

        return new self($other->getAmount() + $this->amount, $this->currency);
    }

    public function toExchangeableMoney(DollarAwareCurrencyInterface $dollarAwareCurrency): ExchangeableMoneyInterface
    {
        if (!$this->hasCurrency($dollarAwareCurrency)) {
            throw new \InvalidArgumentException(
                '$dollarAwareCurrency must be of the same currency in order to transform into exchangeable!'
            );
        }

        return new ExchangeableMoney($this->amount, $dollarAwareCurrency);
    }
}