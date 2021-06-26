<?php

namespace NekoOs\Pood\Support\Traits;

use NekoOs\Decorator\PropertyResolver;
use NekoOs\Decorator\v1\Compatibility;
use NekoOs\Pood\Reflections\Property;
use ReflectionClass;

/**
 * @deprecated
 */
trait Propertyable
{

    public function __get($name)
    {
        return PropertyResolver::from(__CLASS__)
            ->getter($this, $name, [], function (ReflectionClass $reflectionClass) use ($name) {
                return Compatibility::get($reflectionClass, $this, $name);
            });
    }

    public function __set($name, $value)
    {

        return PropertyResolver::from(__CLASS__)
            ->setter($this, $name, [$value], function ($reflection) use ($name, $value) {
                Compatibility::set($reflection, $this, $name, $value);
            });
    }

    public function __isset($name)
    {
        return PropertyResolver::from(__CLASS__)->hasSetter($name);
    }
}
