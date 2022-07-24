<?php

namespace App\Good\Command;

use Nlf\Component\Event\Aggregate\Shared\UuidInterface;

interface GoodAggregateRepositoryInterface
{
    public function get(UuidInterface $uuid): ?GoodAggregate;
    public function findOneByCode(string $goodCode): ?GoodAggregate;
    public function save(GoodAggregate $goodAggregate): void;
}