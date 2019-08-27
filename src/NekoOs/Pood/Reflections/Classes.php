<?php

namespace NekoOs\Pood\Reflections;

use ReflectionClass;
use ReflectionObject;
use Reflector;

class Classes
{
    private static $cache;


    /**
     * @param string $subject
     *
     * @return \Reflector|null
     */
    public static function cache(string $subject): ?Reflector
    {
        return static::$cache[$subject] ?? null;
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public static function isCached(string $class): bool
    {
        return isset(static::$cache[$class]);
    }

    /**
     * @param string|object $subject
     *
     * @return \ReflectionClass|\ReflectionObject
     * @throws \ReflectionException
     */
    public static function reflect($subject): Reflector
    {
        if (is_string($subject) and static::isCached($subject)) {
            $response = static::cache($subject);
        } elseif (is_string($subject)) {
            $response = static::cacheClass($subject);
        } else {
            $response = static::cacheObject($subject);
        }
        return $response;
    }

    /**
     * @param string $class
     *
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    private static function cacheClass(string $class): ReflectionClass
    {
        return static::$cache[$class] = new ReflectionClass($class);
    }

    /**
     * @param object $object
     *
     * @return \ReflectionObject
     */
    private static function cacheObject($object): ReflectionObject
    {
        $reflection = new ReflectionObject($object);
        return static::$cache[$reflection->name] = $reflection;
    }
}
