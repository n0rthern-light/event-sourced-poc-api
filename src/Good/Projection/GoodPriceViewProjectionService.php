<?php

namespace App\Good\Projection;

use Nlf\Component\Event\Aggregate\AbstractAggregateRoot;
use Nlf\Component\Event\Aggregate\ProjectionServiceInterface;

final class GoodPriceViewProjectionService implements ProjectionServiceInterface
{
    public function execute(AbstractAggregateRoot $aggregate, array $events): void
    {
        dd($aggregate, $events);
    }
}