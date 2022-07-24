<?php

namespace App\Good\Query;

use App\Money\DollarAwareCurrencyInterface;
use App\Money\DollarMoney;

class GoodQuery
{
    private GoodViewRepositoryInterface $repository;

    public function __construct(GoodViewRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function queryAll(string $inGoodCode = 'USD'): array
    {
        $views = $this->repository->findAll();

        return \array_map(function(GoodView $view) use ($inGoodCode) {
            return new GoodDto(
                $view->uuid,
                $view->code,
                $view->name,
                $view->lastPriceInUsd,
                $inGoodCode,
                $view->priceUpdatedOn->format('Y-m-d H:i:s')
            );
        }, $views);

    }
}