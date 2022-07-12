<?php

namespace App\Shared\Infrastructure\EventDrivenAggregate;

use Nlf\Component\Event\Aggregate\AbstractAggregateRoot;
use Nlf\Component\Event\Aggregate\EventCollectionInterface;
use Nlf\Component\Event\Aggregate\ProjectionServiceInterface;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Webmozart\Assert\Assert;

final class RoutedProjectionService implements ProjectionServiceInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function execute(AbstractAggregateRoot $aggregate, EventCollectionInterface $events): void
    {
        if (!$this->container->has($aggregate::class)) {

            throw new RuntimeException('Not found any $projectionService for ' . $aggregate::class);
        }

        /** @var ProjectionServiceInterface $projectionService */
        $projectionService = $this->container->get($aggregate::class);

        Assert::isInstanceOf($projectionService, ProjectionServiceInterface::class);
        Assert::notInstanceOf($projectionService, self::class);

        $projectionService->execute($aggregate, $events);
    }
}