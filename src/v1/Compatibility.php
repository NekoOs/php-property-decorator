<?php

namespace NekoOs\Decorator\v1;

use NekoOs\Pood\Exceptions\AccessAttributeException;
use NekoOs\Pood\Exceptions\UndefinedAttributeException;
use NekoOs\Pood\Support\Traits\Propertyable;
use ReflectionClass;
use ReflectionException;
use TypeError;

class Compatibility
{
    public static function set(ReflectionClass $reflectionClass, $object, string $name, $value): void
    {
        $response = null;

        try {
            if ($hasSetter = method_exists($object, $method = 'set' . ucfirst($name))) {
                $object->$method($value);
            } elseif (!$reflectionClass->hasProperty($name)) {
                throw new UndefinedAttributeException("Undefined property: {$reflectionClass->getName()}::$$name");
            } elseif (($property = $reflectionClass->getProperty("name"))->isProtected()) {
                throw new AccessAttributeException("Cannot access protected property: {$reflectionClass->getName()}::$$name");
            } elseif ($property->isPrivate()) {
                throw new AccessAttributeException("Cannot access protected property: {$reflectionClass->getName()}::$$name");
            }
        } catch (TypeError $exception) {
            if (preg_match('/must be of the type ([^,]+), ([^ ]+) given/', $exception->getMessage(), $matches)) {
                $exception = new TypeError("Argument 1 passed to {$reflectionClass->getName()}::$$name $matches[0]");
            }
            throw $exception;
        } catch (ReflectionException $exception) {
            throw new AccessAttributeException("Cannot access protected property: {$reflectionClass->getName()}::$$name");
        }
    }

    public static function get(ReflectionClass $reflectionClass, $object, $name)
    {
        $response = null;
        try {
            if (method_exists($object, $method = 'get' . ucfirst($name))) {
                $response = $object->$method();
            } elseif (!$reflectionClass->hasProperty($name)) {
                throw new UndefinedAttributeException("Undefined property: {$reflectionClass->getName()}::$$name");
            } elseif (($property = $reflectionClass->getProperty("name"))->isProtected()) {
                throw new AccessAttributeException("Cannot access protected property: {$reflectionClass->getName()}::$$name");
            } elseif ($property->isPrivate()) {
                throw new AccessAttributeException("Cannot access protected property: {$reflectionClass->getName()}::$$name");
            }
        } catch (ReflectionException $exception) {
            throw new AccessAttributeException("Cannot access protected property: {$reflectionClass->getName()}::$$name");
        }

        return $response;
    }
}