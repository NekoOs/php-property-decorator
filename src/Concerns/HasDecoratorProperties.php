<?php

namespace NekoOs\Decorator\Concerns;

use NekoOs\Decorator\PropertyResolver;

trait HasDecoratorProperties
{

    public function __isset($name)
    {
        return PropertyResolver::from(__CLASS__)->hasSetter($name);
    }

    public function __get($name)
    {
        return PropertyResolver::from(__CLASS__)->getter($this, $name);
    }

    public function __set($name, $value)
    {
        return PropertyResolver::from(__CLASS__)->setter($this, $name, [$value]);
    }
}