<?php

namespace NekoOs\Pood\Support\Traits;

use Exception;
use NekoOs\Pood\Reflections\Property;

trait Propertyable
{
    public function __get($name)
    {
        return Property::read($this, $name);
    }
    
    public function __set($name, $value)
    {
        return Property::write($this, $name, $value);
    }
    
    public function __isset($name)
    {
        return Property::hasRead(static::class, $name);
    }
}
