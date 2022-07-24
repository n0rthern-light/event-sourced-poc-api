<?php

namespace App\Shared\Infrastructure\EventDrivenAggregate;

use Nlf\Component\Event\Aggregate\Shared\UuidInterface;

final class StringUuid implements UuidInterface
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