<?php

namespace App\Good\Query;

use App\Shared\Serializer\JsonSerializerInterface;
use Predis\ClientInterface;

final class RedisGoodViewRepository implements GoodViewRepositoryInterface
{
    private const KEY_PATTERN = 'view:good:%s';

    private ClientInterface $client;
    private JsonSerializerInterface $jsonSerializer;

    public function __construct(ClientInterface $client, JsonSerializerInterface $jsonSerializer)
    {
        $this->client = $client;
        $this->jsonSerializer = $jsonSerializer;
    }

    public function findOneByGoodCode(string $goodCode): ?GoodView
    {
        $serialized = $this->client->get(
            \sprintf(self::KEY_PATTERN, $goodCode)
        );

        if (!$serialized) {
            return null;
        }

        return $this->jsonSerializer->deserialize($serialized, GoodView::class);
    }

    public function findAll(): array
    {
        dump($this->client->keys(\sprintf(self::KEY_PATTERN, '*')));
        return [];
    }

    public function save(GoodView $goodPriceView): void
    {
        $serialized = $this->jsonSerializer->serialize($goodPriceView);
        $key = \sprintf(self::KEY_PATTERN, $goodPriceView->getCode());

        $this->client->set($key, $serialized);
    }
}