<?php

namespace App\Shared\Infrastructure\EventDrivenAggregate;

use DateTimeInterface;
use Nlf\Component\Event\Aggregate\AggregateEventInterface;
use Nlf\Component\Event\Aggregate\AggregateUuidInterface;
use Nlf\Component\Event\Aggregate\EventFactoryInterface;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Webmozart\Assert\Assert;

final class RoutedEventFactory implements EventFactoryInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function create(
        string $eventName,
        AggregateUuidInterface $aggregateUuid,
        DateTimeInterface $createdAt,
        array $payload
    ): AggregateEventInterface {
        if (!$this->container->has($eventName)) {
            throw new RuntimeException('Factory for ' . $eventName . ' not found!');
        }

        /** @var EventFactoryInterface $factory */
        $factory = $this->container->get($eventName);

        Assert::isInstanceOf($factory, EventFactoryInterface::class);
        Assert::notInstanceOf($factory, self::class);

        return $factory->create(
            $eventName,
            $aggregateUuid,
            $createdAt,
            $payload
        );
    }
}