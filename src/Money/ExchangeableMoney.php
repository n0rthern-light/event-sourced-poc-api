<?php

namespace App\Money;

class ExchangeableMoney implements ExchangeableMoneyInterface
{
    use MoneyTrait;

    public function __construct(float $amount, DollarAwareCurrencyInterface $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmountInDollar(): float
    {
        return $this->amount / $this->currency->toDollarRatio();
    }

    public function toDollar(): ExchangeableMoneyInterface
    {
        return $this->toCurrency(new DollarCurrency());
    }

    public function toCurrency(DollarAwareCurrencyInterface $otherCurrency): ExchangeableMoneyInterface
    {
        if ($otherCurrency->getCode() === $this->currency->getCode()) {
            return $this;
        }

        return new self(
            $this->getAmountInDollar() * $otherCurrency->toDollarRatio(),
            $otherCurrency
        );
    }

    /**
     * @param ExchangeableMoneyInterface $other
     */
    public function add(MoneyInterface $other): ExchangeableMoneyInterface
    {
        if (!($other instanceof ExchangeableMoneyInterface)) {
            throw new \InvalidArgumentException(
                '$other must be an instance of ' . ExchangeableMoneyInterface::class
            );
        }

        if ($other->hasCurrency($this->currency)) {
            return new self($other->getAmount() + $this->getAmount(), $this->currency);
        }

        $selfAmountInDollar = $this->getAmountInDollar();
        $otherAmountInDollar = $other->getAmountInDollar();

        return (new self($selfAmountInDollar + $otherAmountInDollar, new DollarCurrency()))
            ->toCurrency($this->currency);
    }

    public function toMoney(): MoneyInterface
    {
        return new Money($this->amount, $this->currency);
    }

    public function toExchangeableMoney(DollarAwareCurrencyInterface $dollarAwareCurrency): ExchangeableMoneyInterface
    {
        return $this;
    }
}
