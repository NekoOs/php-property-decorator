<?php

namespace NekoOs\Pood\Reflections;

use NekoOs\Support\BitwiseFlag;
use Reflection;
use ReflectionClass;
use ReflectionMethod;

class Method
{
    use Cacheable;

    const IS_ANY = 2047;


    /**
     * @param        $subject
     * @param string $name
     *
     * @return bool
     * @throws \ReflectionException
     */
    public static function isPublic($subject, string $name)
    {
        $methods = static::getPublicMethods($subject);
        return array_key_exists($name, $methods);
    }

    private static function mapRecords(array $records)
    {
        foreach ($records as $record) {
            $response[$record->name] = $record;
        }
        return $response ?? [];
    }

    /**
     * @param string|object $subject
     *
     * @return array
     * @throws \ReflectionException
     */
    private static function getPublicMethods($subject): array
    {
        $reflection = Classes::reflect($subject);
        if (!static::isReflectionCacheable($reflection)) {
            $records = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
            $response = static::mapRecords($records);
        } elseif (!static::matchCacheFlag($reflection->name, ReflectionMethod::IS_PUBLIC)) {
            $records = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
            static::setCacheFlag($reflection->name, ReflectionMethod::IS_PUBLIC, true);
            static::setCacheRecords($reflection->name, $records, ReflectionMethod::IS_PUBLIC);
        }
        $a = static::getCacheRecords($subject, ReflectionMethod::IS_PUBLIC);
        return $response ?? static::getCacheRecords($subject, ReflectionMethod::IS_PUBLIC);
    }
}
