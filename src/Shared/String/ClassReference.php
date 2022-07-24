<?php

namespace App\Shared\String;

final class ClassReference
{
    public static function getShortClassName(string $fullyQualifiedClassName): string
    {
        return \substr($fullyQualifiedClassName, \strrpos($fullyQualifiedClassName, '\\')+1);
    }
}