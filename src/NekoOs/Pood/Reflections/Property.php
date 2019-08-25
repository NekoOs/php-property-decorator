<?php


namespace NekoOs\Pood\Reflections;


use NekoOs\Pood\Support\Contracts\ReadPropertyable;
use ReflectionProperty;

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

    public static function hasWrite($subject, string $string): bool
    {
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
     * @return callable
     */
    private static function makeCallableReadMutator(string $class, string $name): string
    {
        $mutator = static::$cache['mutator']['read'][$name] ?? null;
        if (empty($mutator)) {
            $mutator = static::skeletonCallableReadMutator($name);
        }
        return $mutator;
    }

    private static function skeletonCallableReadMutator(string $name): string
    {
        return 'get' . studly_case($name);
    }
}
