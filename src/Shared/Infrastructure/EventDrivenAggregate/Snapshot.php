<?php

namespace App\Shared\Infrastructure\EventDrivenAggregate;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Nlf\Component\Event\Aggregate\Shared\UuidInterface;

#[ORM\Entity(repositoryClass: SnapshotRepository::class)]
class Snapshot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'integer')]
    private int $lastTakenIntoAccountEventId;

    #[ORM\Column(type: 'string')]
    private string $aggregateUuid;

    #[ORM\Column(type: 'string')]
    private string $aggregateName;

    #[ORM\Column(type: 'json')]
    private array $aggregateState = [];

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    public function __construct(
        ?int $id,
        int $lastTakenIntoAccountEventId,
        UuidInterface $aggregateUuid,
        string $aggregateName,
        array $aggregateState,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->lastTakenIntoAccountEventId = $lastTakenIntoAccountEventId;
        $this->aggregateUuid = (string)$aggregateUuid;
        $this->aggregateName = $aggregateName;
        $this->aggregateState = $aggregateState;
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function setLastTakenIntoAccountEventId(int $lastTakenIntoAccountEventId): void
    {
        $this->lastTakenIntoAccountEventId = $lastTakenIntoAccountEventId;
    }

    public function setAggregateName(string $aggregateName): void
    {
        $this->aggregateName = $aggregateName;
    }

    public function setAggregateState(array $aggregateState): void
    {
        $this->aggregateState = $aggregateState;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastTakenIntoAccountEventId(): int
    {
        return $this->lastTakenIntoAccountEventId;
    }

    public function getAggregateUuid(): UuidInterface
    {
        return new StringUuid($this->aggregateUuid);
    }

    public function getAggregateName(): string
    {
        return $this->aggregateName;
    }

    public function getAggregateState(): array
    {
        return $this->aggregateState;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
