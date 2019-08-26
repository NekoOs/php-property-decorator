<?php


namespace NekoOs\Pood\Reflections;

use NekoOs\Pood\Support\Contracts\ReadPropertyable;
use NekoOs\Pood\Support\Contracts\WritePropertyable;

class Property
{

    use Cacheable;
    /**
     * @var array
     */
    private static $cache;

    /**
     * @param $subject
     * @param $name
     *
     * @return bool
     * @throws \ReflectionException
     */
    public static function hasRead($subject, $name): bool
    {
        $reflection = Classes::reflect($subject);
        if (is_subclass_of($subject, ReadPropertyable::class)) {
            $response = static::hasReadMutator($reflection->name, $name);
        } else {
            $response = isset($subject->$name) || $reflection->getProperty($name)->isPublic();
        }
        return $response;
    }

    public static function hasWrite($subject, string $name): bool
    {
        $reflection = Classes::reflect($subject);
        if (is_subclass_of($subject, WritePropertyable::class)) {
            $response = static::hasWriteMutator($reflection->name, $name);
        } else {
            $response = isset($subject->$name) || $reflection->getProperty($name)->isPublic();
        }
        return $response;
    }

    /**
     * @param string $class
     * @param string $name
     *
     * @return bool
     * @throws \ReflectionException
     */
    private static function hasReadMutator(string $class, string $name): bool
    {
        $mutator = static::makeCallableReadMutator($class, $name);
        return Method::isPublic($class, $mutator);
    }

    /**
     * @param string $class
     * @param string $name
     *
     * @return bool
     * @throws \ReflectionException
     */
    private static function hasWriteMutator(string $class, string $name): bool
    {
        $mutator = static::makeCallableWriteMutator($class, $name);
        return Method::isPublic($class, $mutator);
    }

    /**
     * @param string $class
     * @param string $name
     *
     * @return callable
     */
    private static function makeCallableReadMutator(string $class, string $name): string
    {
        $mutator = static::$cache[$class]['mutator']['read'][$name] ?? null;
        if (empty($mutator)) {
            $mutator = static::skeletonCallableReadMutator($name);
        }
        return $mutator;
    }

    /**
     * @param string $class
     * @param string $name
     *
     * @return callable
     */
    private static function makeCallableWriteMutator(string $class, string $name): string
    {
        $mutator = static::$cache[$class]['mutator']['read'][$name] ?? null;
        if (empty($mutator)) {
            $mutator = static::skeletonCallableWriteMutator($name);
        }
        return $mutator;
    }

    private static function skeletonCallableReadMutator(string $name): string
    {
        return 'get' . studly_case($name);
    }

    private static function skeletonCallableWriteMutator(string $name): string
    {
        return 'set' . studly_case($name);
    }
}
