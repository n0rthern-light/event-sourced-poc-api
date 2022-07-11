<?php

namespace App\Good\Query;

use App\Money\DollarAwareCurrencyInterface;

class GoodPriceQuery
{
    private GoodPriceViewRepositoryInterface $repository;

    public function __construct(GoodPriceViewRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function query(string $goodCode, DollarAwareCurrencyInterface $inCurrency): ?GoodPriceDto
    {
        $view = $this->repository->findOneByGoodCode($goodCode);

        if (!$view) {
            return null;
        }

        return new GoodPriceDto(
            $view->getCode(),
            $view->getName(),
            $view->getLastPrice()->toCurrency($inCurrency),
            $view->getPriceUpdatedOn()
        );
    }
}