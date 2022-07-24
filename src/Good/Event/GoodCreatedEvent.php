<?php

namespace App\Good\Event;

use Nlf\Component\Event\Aggregate\Event\AbstractEvent;
use Nlf\Component\Event\Aggregate\Event\EventProps;

class GoodCreatedEvent extends AbstractEvent
{
    private string $goodCode;
    private string $goodName;

    public function __construct(EventProps $props, string $goodCode, string $goodName)
    {
        parent::__construct($props);
        $this->goodCode = $goodCode;
        $this->goodName = $goodName;
    }

    public function getGoodCode(): string
    {
        return $this->goodCode;
    }

    public function getGoodName(): string
    {
        return $this->goodName;
    }

    public function getJsonPayload(): array
    {
        return [
            'goodCode' => $this->goodCode,
            'goodName' => $this->goodName,
        ];
    }
}