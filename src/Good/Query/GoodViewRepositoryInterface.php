<?php

namespace App\Good\Query;

interface GoodViewRepositoryInterface
{
    public function get(string $uuid): ?GoodView;
    /** @return GoodView[] */
    public function findAll(): array;
    public function save(GoodView $goodPriceView): void;
}