<?php

namespace App\Good\Query;

interface GoodViewRepositoryInterface
{
    public function findOneByGoodCode(string $goodCode): ?GoodView;
    /** @return GoodView[] */
    public function findAll(): array;
    public function save(GoodView $goodPriceView): void;
}