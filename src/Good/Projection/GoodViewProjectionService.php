<?php

namespace App\Good\Projection;

use App\Good\Event\GoodPriceUpdatedEvent;
use App\Good\Query\GoodView;
use App\Good\Query\GoodViewRepositoryInterface;
use App\Money\DollarMoney;
use Nlf\Component\Event\Aggregate\AbstractAggregateRoot;
use Nlf\Component\Event\Aggregate\AggregateEventInterface;
use Nlf\Component\Event\Aggregate\EventCollectionInterface;
use Nlf\Component\Event\Aggregate\ProjectionServiceInterface;

final class GoodViewProjectionService implements ProjectionServiceInterface
{
    private GoodViewRepositoryInterface $goodViewRepository;

    public function __construct(GoodViewRepositoryInterface $goodViewRepository)
    {
        $this->goodViewRepository = $goodViewRepository;
    }

    /** @param GoodAggregate $aggregate */
    public function execute(AbstractAggregateRoot $aggregate, EventCollectionInterface $events): void
    {
        $uniqueEvents = $events->lastAndUnique()->sortChronologically();

        /** @var AggregateEventInterface $uniqueEvent */
        foreach($uniqueEvents as $uniqueEvent) {
            if ($uniqueEvent->getEventName() === 'GoodPriceUpdatedEvent') {
                /** @var GoodPriceUpdatedEvent $uniqueEvent */
                $goodPriceView = new GoodView(
                    'USD',
                    'U.S. Dollar',
                    new DollarMoney($uniqueEvent->getPriceInUsd()),
                    $uniqueEvent->getCreatedAt()
                );
            }
        }

        $this->goodViewRepository->save($goodPriceView);
        $this->goodViewRepository->findAll();
    }
}