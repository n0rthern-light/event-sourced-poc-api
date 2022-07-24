<?php

namespace App\Good\Event;

use App\Good\Command\GoodAggregate;
use App\Good\Query\GoodView;
use App\Good\Query\GoodViewRepositoryInterface;
use Nlf\Component\Event\Aggregate\Aggregate\AbstractAggregateRoot;
use Nlf\Component\Event\Aggregate\Event\EventCollectionInterface;
use Nlf\Component\Event\Aggregate\Event\EventProjectionServiceInterface;

final class GoodViewProjectionService implements EventProjectionServiceInterface
{
    private GoodViewRepositoryInterface $goodViewRepository;

    public function __construct(GoodViewRepositoryInterface $goodViewRepository)
    {
        $this->goodViewRepository = $goodViewRepository;
    }

    /** @param GoodAggregate $aggregate */
    public function execute(AbstractAggregateRoot $aggregate, EventCollectionInterface $events): void
    {
        /** @var GoodPriceUpdatedEvent|null $lastPriceUpdated */
        $lastPriceUpdated = $events->filterByEventName('GoodPriceUpdatedEvent')->last();

        $goodPriceView = new GoodView;
        $goodPriceView->uuid = (string)$aggregate->getUuid();
        $goodPriceView->name = $aggregate->getGoodName();
        $goodPriceView->code = $aggregate->getGoodCode();
        $goodPriceView->lastPriceInUsd = $aggregate->getPrice()->getAmount();
        $goodPriceView->priceUpdatedOn = $lastPriceUpdated ? $lastPriceUpdated->getCreatedAt() : new \DateTimeImmutable();

        $this->goodViewRepository->save($goodPriceView);
    }
}