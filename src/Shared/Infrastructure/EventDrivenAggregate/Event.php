<?php

namespace App\Shared\Infrastructure\EventDrivenAggregate;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Nlf\Component\Event\Aggregate\Shared\UuidInterface;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string')]
    private string $eventUuid;

    #[ORM\Column(type: 'string')]
    private string $aggregateUuid;

    #[ORM\Column(type: 'string', length: 255)]
    private string $eventName;

    #[ORM\Column(type: 'json')]
    private array $payload = [];

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    public function __construct(
        ?int $id,
        UuidInterface $eventUuid,
        UuidInterface $aggregateUuid,
        string $eventName,
        array $payload,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->eventUuid = (string)$eventUuid;
        $this->aggregateUuid = (string)$aggregateUuid;
        $this->eventName = $eventName;
        $this->payload = $payload;
        $this->createdAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt->format('Y-m-d H:i:s'));
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAggregateUuid(): UuidInterface
    {
        return new StringUuid($this->aggregateUuid);
    }

    public function getEventUuid(): UuidInterface
    {
        return new StringUuid($this->eventUuid);
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function getPayload(): ?array
    {
        return $this->payload;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }
}
