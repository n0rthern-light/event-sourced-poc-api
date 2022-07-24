<?php

namespace App\Shared\Infrastructure\EventDrivenAggregate;

use Nlf\Component\Event\Aggregate\Event\EventFactoryInterface;
use Nlf\Component\Event\Aggregate\Event\EventInterface;
use Nlf\Component\Event\Aggregate\Event\EventProps;
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
        EventProps $props,
        array $payload
    ): EventInterface {
        if (!$this->container->has($eventName)) {
            throw new RuntimeException('Factory for ' . $eventName . ' not found!');
        }

        /** @var EventFactoryInterface $factory */
        $factory = $this->container->get($eventName);

        Assert::isInstanceOf($factory, EventFactoryInterface::class);
        Assert::notInstanceOf($factory, self::class);

        return $factory->create(
            $eventName,
            $props,
            $payload
        );
    }
}