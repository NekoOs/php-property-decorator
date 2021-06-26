<?php

namespace NekoOs\Decorator;

use ReflectionClass;

class PropertyResolver
{
    protected static $container;

    public static function from($class): Property
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return static::$container[$class] = static::$container[$class] ?? new Property(new ReflectionClass($class));
    }
}