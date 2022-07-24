<?php

namespace App\Good\Snapshot;

use App\Good\Command\GoodAggregate;
use Nlf\Component\Event\Aggregate\Aggregate\AbstractAggregateRoot;
use Nlf\Component\Event\Aggregate\Snapshot\SnapshotFactoryInterface;
use Nlf\Component\Event\Aggregate\Snapshot\SnapshotInterface;
use Nlf\Component\Event\Aggregate\Snapshot\SnapshotProps;

final class GoodAggregateSnapshotFactory implements SnapshotFactoryInterface
{
    /** @param GoodAggregate $aggregate */
    public function createByAggregate(AbstractAggregateRoot $aggregate, int $lastEventId): SnapshotInterface
    {
        return new GoodAggregateSnapshot(
            new SnapshotProps(
                $lastEventId,
                $aggregate->getUuid(),
                'GoodAggregate',
                [
                    'goodCode' => $aggregate->getGoodCode(),
                    'goodName' => $aggregate->getGoodName(),
                    'priceInUsd' => $aggregate->getPrice()->getAmount(),
                ]
            )
        );
    }

    public function create(SnapshotProps $props): SnapshotInterface
    {
        return new GoodAggregateSnapshot($props);
    }
}