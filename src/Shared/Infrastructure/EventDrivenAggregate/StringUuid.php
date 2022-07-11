<?php

namespace App\Shared\Infrastructure\EventDrivenAggregate;

use Nlf\Component\Event\Aggregate\AggregateUuidInterface;

final class StringUuid implements AggregateUuidInterface
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}