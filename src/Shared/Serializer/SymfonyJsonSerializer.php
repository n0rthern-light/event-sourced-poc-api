<?php

namespace App\Shared\Serializer;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializerBuilder;

final class SymfonyJsonSerializer implements JsonSerializerInterface
{
    private SerializerInterface $serializer;

    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()->build();
    }

    public function serialize(object $object): string
    {
        return $this->serializer->serialize($object, 'json');
    }

    public function deserialize(string $jsonString, string $classReference)
    {
        return $this->serializer->deserialize($jsonString, $classReference, 'json');
    }
}