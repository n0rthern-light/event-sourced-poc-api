<?php

namespace App\Good\Command;

use App\Money\DollarMoney;
use App\Shared\CQRS\CommandBus\CommandHandlerInterface;
use App\Shared\Infrastructure\EventDrivenAggregate\StringUuid;
use Nlf\Component\Event\Aggregate\AggregateEventsHandler;
use Nlf\Component\Event\Aggregate\AggregateUuidInterface;

final class UpdateGoodPriceHandler implements CommandHandlerInterface
{
    private AggregateEventsHandler $aggregateEventsHandler;

    public function __construct(AggregateEventsHandler $aggregateEventsHandler)
    {
        $this->aggregateEventsHandler = $aggregateEventsHandler;
    }

    public function __invoke(UpdateGoodPriceCommand $command): void
    {
        $goodPriceAggregate = new GoodPriceAggregate($this->buildAggregateUuid($command->goodCode));
        $goodPriceAggregate->updatePrice(new DollarMoney($command->priceInUsd));
        $this->aggregateEventsHandler->commitAggregateEvents($goodPriceAggregate);
    }

    private function buildAggregateUuid(string $goodCode): AggregateUuidInterface
    {
        return new StringUuid('GOOD:' . $goodCode);
    }
}