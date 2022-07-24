<?php

namespace App\Good\Command;

use App\Money\DollarMoney;
use App\Shared\CQRS\CommandBus\CommandHandlerInterface;
use Nlf\Component\Event\Aggregate\Aggregate\AggregateEventsHandler;

final class CreateGoodHandler implements CommandHandlerInterface
{
    private GoodAggregateRepositoryInterface $repository;
    private AggregateEventsHandler $aggregateEventsHandler;

    public function __construct(
        GoodAggregateRepositoryInterface $repository,
        AggregateEventsHandler $aggregateEventsHandler
    ) {
        $this->repository = $repository;
        $this->aggregateEventsHandler = $aggregateEventsHandler;
    }

    public function __invoke(CreateGoodCommand $command): void
    {
        $goodAggregate = $this->repository->findOneByCode($command->goodCode);

        if ($goodAggregate) {
            throw new \InvalidArgumentException('Good of code ' . $command->goodCode . ' already exists.');
        }

        $goodPriceAggregate = new GoodAggregate(
            null,
            $command->goodCode,
            $command->goodName,
            new DollarMoney($command->priceInUsd)
        );

        $this->aggregateEventsHandler->commitAggregateEvents($goodPriceAggregate);
    }
}