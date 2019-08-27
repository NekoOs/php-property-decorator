<?php

namespace NekoOs\Pood\Reflections;


use NekoOs\Pood\Support\BitwiseFlag;
use Reflection;
use ReflectionClass;

trait Cacheable
{

    private static $cache;

    /**
     * @param string $class
     * @param int    $bit
     *
     * @return array
     */
    protected static function getCacheRecords(string $class, int $bit = self::IS_ANY): array
    {
        $response = [];
        $flags = Reflection::getModifierNames($bit);
        foreach ($flags as $flag) {
            $response = array_merge($response, static::$cache[$class][self::class][$flag] ?? []);
        }
        return $response;
    }

    /**
     * @param $reflection
     *
     * @return bool
     */
    protected static function isReflectionCacheable($reflection)
    {
        return $reflection instanceof ReflectionClass;
    }

    /**
     * @param string $class
     * @param int    $bit
     *
     * @return bool
     */
    protected static function matchCacheFlag(string $class, int $bit): bool
    {
        return BitwiseFlag::match(static::$cache[$class]['flagged'] ?? 0, $bit);
    }


    /**
     * @param string $class
     * @param int    $bit
     * @param bool   $value
     */
    protected static function setCacheFlag(string $class, int $bit, bool $value)
    {
        $flagged = static::$cache[$class]['flagged'] ?? 0;
        BitwiseFlag::set($flagged, $bit, $value);
        static::$cache[$class]['flagged'] = $flagged;
    }

    /**
     * @param string       $class
     * @param \Reflector[] $records
     * @param int          $bit
     */
    private static function setCacheRecords(string $class, array $records, int $bit = self::IS_ANY)
    {
        foreach ($records as $record) {
            $flags = Reflection::getModifierNames($bit);
            foreach ($flags as $flag) {
                static::$cache[$class][self::class][$flag][$record->name] = $record;
            }
        }
    }
}
