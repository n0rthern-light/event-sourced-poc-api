<?php

namespace App\Shared\Infrastructure\EventDrivenAggregate;

use App\Shared\String\ClassReference;
use Nlf\Component\Event\Aggregate\Aggregate\AbstractAggregateRoot;
use Nlf\Component\Event\Aggregate\Event\EventCollectionInterface;
use Nlf\Component\Event\Aggregate\Event\EventProjectionServiceInterface;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Webmozart\Assert\Assert;

final class RoutedProjectionService implements EventProjectionServiceInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function execute(AbstractAggregateRoot $aggregate, EventCollectionInterface $events): void
    {
        $serviceReference = ClassReference::getShortClassName($aggregate::class);

        if (!$this->container->has($serviceReference)) {

            throw new RuntimeException('Not found any $projectionService for ' . $aggregate::class);
        }

        /** @var EventProjectionServiceInterface $projectionService */
        $projectionService = $this->container->get($serviceReference);

        Assert::isInstanceOf($projectionService, EventProjectionServiceInterface::class);
        Assert::notInstanceOf($projectionService, self::class);

        $projectionService->execute($aggregate, $events);
    }
}