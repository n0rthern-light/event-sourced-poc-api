<?php

namespace App\Good\Query;

use App\Money\DollarAwareCurrencyInterface;

class GoodQuery
{
    private GoodViewRepositoryInterface $repository;

    public function __construct(GoodViewRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function query(string $goodCode, DollarAwareCurrencyInterface $inCurrency): ?GoodDto
    {
        $view = $this->repository->findOneByGoodCode($goodCode);

        if (!$view) {
            return null;
        }

        return new GoodDto(
            $view->getCode(),
            $view->getName(),
            $view->getLastPrice()->toCurrency($inCurrency),
            $view->getPriceUpdatedOn()
        );
    }
}