<?php

namespace App\Shared\Serializer;

interface JsonSerializerInterface
{
    public function serialize(object $object): string;
    public function deserialize(string $jsonString, string $classReference);
}