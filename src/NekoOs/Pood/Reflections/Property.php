<?php

namespace NekoOs\Pood\Reflections;

use Closure;
use NekoOs\Pood\Support\Contracts\ReadPropertyable;
use NekoOs\Pood\Support\Contracts\WritePropertyable;
use TypeError;

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
        $isPublicAttribute = $reflection->hasProperty($name) 
                && $reflection->getProperty($name)->isPublic();
        
        if ($isPublicAttribute) {
            $response = true;
        } elseif (is_subclass_of($subject, ReadPropertyable::class)) {
            $response = static::hasReadMutator($reflection->name, $name);
        } else {
            $response = isset($subject->$name);
        }
        return $response;
    }

    public static function hasWrite($subject, string $name): bool
    {
        $reflection = Classes::reflect($subject);
        $isPublicAttribute = $reflection->hasProperty($name) 
                && $reflection->getProperty($name)->isPublic();
        
        if ($isPublicAttribute) {
            $response = true;
        } elseif (is_subclass_of($subject, WritePropertyable::class)) {
            $response = static::hasWriteMutator($reflection->name, $name);
        } else {
            $response = isset($subject->$name);
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
    public static function hasReadMutator(string $class, string $name): bool
    {
        $response = false;
        if (is_subclass_of($class, ReadPropertyable::class)) {
            $mutator = static::makeCallableReadMutator($class, $name);
            $response = Method::isPublic($class, $mutator);
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
    private static function hasWriteMutator(string $class, string $name): bool
    {
        $response = false;
        if (is_subclass_of($class, WritePropertyable::class)) {
            $mutator = static::makeCallableWriteMutator($class, $name);
            $response = Method::isPublic($class, $mutator);
        }
        return $response;
    }

    /**
     * @param string $class
     * @param string $name
     *
     * @return callable
     */
    private static function makeCallableReadMutator(string $class, string $name)
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
        $mutator = static::$cache[$class]['mutator']['write'][$name] ?? null;
        if (empty($mutator)) {
            $mutator = static::skeletonCallableWriteMutator($name);
        }
        return $mutator;
    }

    private static function skeletonCallableReadMutator(string $name)
    {
        return 'get' . studly_case($name);
    }

    private static function skeletonCallableWriteMutator(string $name): string
    {
        return 'set' . studly_case($name);
    }

    /**
     * @param object $object
     * @param string $name
     * 
     * @return \Closure
     * @throws \TypeError
     */
    public static function makeReadMutator($object, string $name) : Closure
    {
        $mutator = static::makeCallableReadMutator(get_class($object), $name);
        if (is_string($mutator)) {
            $method = Closure::fromCallable([$object, $mutator]);
        } elseif (is_callable($mutator)) {
            $method = Closure::bind($mutator, $object);
        } else {
            $mutator = var_export($mutator, true);
            throw new TypeError("Failed to create closure from callable: the generated read mutator is invalid [$mutator]");
        }
        return $method;
    }

    /**
     * 
     * @param object $object
     * @param string $name
     * 
     * @return \Closure
     * @throws \TypeError
     */
    public static function makeWriteMutator($object, string $name) : Closure
    {
        $mutator = static::makeCallableWriteMutator(get_class($object), $name);
        if (is_string($mutator)) {
            $method = Closure::fromCallable([$object, $mutator]);
        } elseif (is_callable($mutator)) {
            $method = Closure::bind($mutator, $object);
        } else {
            $mutator = var_export($mutator, true);
            throw new TypeError("Failed to create closure from callable: the generated write mutator is invalid [$mutator]");
        }
        return $method;
    }
    
    public function read($object, $name, $force = false)
    {
        $reflection = Classes::reflect($force ? $object : get_class($object));
        $isPublicAttribute = $reflection->hasProperty($name) 
                && $reflection->getProperty($name)->isPublic();
        
        if ($isPublicAttribute) {
            $response = $object->$name;
        } elseif (static::hasReadMutator(get_class($object), $name)) {
            $response = call_user_func(Property::makeReadMutator($object, $name));
        } else {
            throwNewException('property not exist');
        }
        return $response;
    }

    public static function write($object, $name, $value, $force = false)
    {
        $reflection = Classes::reflect($force ? $object : get_class($object));
        $isPublicAttribute = $reflection->hasProperty($name) 
                && $reflection->getProperty($name)->isPublic();
        
        if ($isPublicAttribute) {
            $response = $object->$name;
        } elseif (static::hasWriteMutator(get_class($object), $name)) {
            $response = call_user_func(Property::makeWriteMutator($object, $name), $value);
        } else {
            throwNewException('property not exist');
        }
    }

}
