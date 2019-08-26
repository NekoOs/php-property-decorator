<?php

namespace NekoOs\Pood\Support\Traits;

use NekoOs\Pood\Reflections\Property;

class Propertyable
{
    public function __get($name)
    {
        if (Property::hasRead(static::class, $name)) {
            
        }
    }
    
    public function __set($name, $value)
    {
        if (Property::hasWrite(static::class, $name)) {
            
        }
    }
    
    public function __isset($name)
    {
        return Property::hasRead(static::class, $name);
    }
}
