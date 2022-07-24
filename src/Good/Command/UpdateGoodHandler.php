<?php

namespace App\Good\Command;

use App\Money\DollarMoney;
use App\Shared\CQRS\CommandBus\CommandHandlerInterface;

final class UpdateGoodHandler implements CommandHandlerInterface
{
    private GoodAggregateRepositoryInterface $repository;

    public function __construct(GoodAggregateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UpdateGoodCommand $command): void
    {
        $goodAggregate = $this->repository->findOneByCode($command->goodCode);

        if (!$goodAggregate) {
            throw new \InvalidArgumentException('Good of code ' . $command->goodCode . ' not found.');
        }

        $goodAggregate->updatePrice(new DollarMoney($command->priceInUsd));

        $this->repository->save($goodAggregate);
    }
}