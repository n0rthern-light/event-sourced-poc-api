<?php

namespace App\Shared\Infrastructure\EventDrivenAggregate;

use App\Shared\String\ClassReference;
use Nlf\Component\Event\Aggregate\Aggregate\AbstractAggregateRoot;
use Nlf\Component\Event\Aggregate\Snapshot\SnapshotFactoryInterface;
use Nlf\Component\Event\Aggregate\Snapshot\SnapshotInterface;
use Nlf\Component\Event\Aggregate\Snapshot\SnapshotProps;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Webmozart\Assert\Assert;

final class RoutedSnapshotFactory implements SnapshotFactoryInterface
{
    private ContainerInterface $serviceLocator;

    public function __construct(ContainerInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function createByAggregate(AbstractAggregateRoot $aggregate, int $lastEventId): SnapshotInterface
    {
        $serviceReference = ClassReference::getShortClassName($aggregate::class);
        $factory = $this->resolveFactory($serviceReference);

        return $factory->createByAggregate($aggregate, $lastEventId);
    }

    public function create(SnapshotProps $props): SnapshotInterface
    {
        $factory = $this->resolveFactory($props->getAggregateName());

        return $factory->create($props);
    }

    private function resolveFactory(string $serviceReference): SnapshotFactoryInterface
    {
        if (!$this->serviceLocator->has($serviceReference)) {
            throw new RuntimeException('Snapshot Factory for ' . $serviceReference . ' not found!');
        }

        /** @var SnapshotFactoryInterface $factory */
        $factory = $this->serviceLocator->get($serviceReference);

        Assert::isInstanceOf($factory, SnapshotFactoryInterface::class);
        Assert::notInstanceOf($factory, self::class);

        return $factory;
    }
}