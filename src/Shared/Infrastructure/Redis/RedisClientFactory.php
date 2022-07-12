<?php

namespace App\Shared\Infrastructure\Redis;

use Predis\Client;
use Predis\ClientInterface;

class RedisClientFactory
{
    public static function create(string $uri): ClientInterface
    {
        return new Client($uri);
    }
}