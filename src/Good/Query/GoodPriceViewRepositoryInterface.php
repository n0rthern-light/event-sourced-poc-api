<?php

namespace App\Good\Query;

interface GoodPriceViewRepositoryInterface
{
    public function findOneByGoodCode(string $goodCode): ?GoodPriceView;
    /** @return GoodPriceView[] */
    public function findAll(): array;
    public function save(GoodPriceView $goodPriceView): void;
}