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

    public function get(string $uuid): ?GoodView
    {
        $serialized = $this->client->get(
            \sprintf(self::KEY_PATTERN, $uuid)
        );

        if (!$serialized) {
            return null;
        }

        return $this->jsonSerializer->deserialize($serialized, GoodView::class);
    }

    public function findAll(): array
    {
        $keys = $this->client->keys(\sprintf(self::KEY_PATTERN, '*'));
        $all = [];

        foreach($keys as $key) {
            $serialized = $this->client->get($key);

            if (!$serialized) {
                continue;
            }

            $all[] = $this->jsonSerializer->deserialize($serialized, GoodView::class);
        }

        return $all;
    }

    public function save(GoodView $goodPriceView): void
    {
        $serialized = $this->jsonSerializer->serialize($goodPriceView);
        $key = \sprintf(self::KEY_PATTERN, $goodPriceView->uuid);

        $this->client->set($key, $serialized);
    }
}