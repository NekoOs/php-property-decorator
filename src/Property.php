<?php

namespace NekoOs\Decorator;

use Closure;
use Jasny\PhpdocParser\PhpdocParser;
use Jasny\PhpdocParser\Set\PhpDocumentor;
use Jasny\PhpdocParser\Tag\MultiTag;
use ReflectionClass;

class Property
{

    /**
     * @var array
     */
    protected $notations;
    /**
     * @var ReflectionClass
     */
    private $container;

    public function __construct(ReflectionClass $container)
    {
        $doc = $container->getDocComment();

        $customTags = [
            new MultiTag('properties', new PropertyTag('property'), 'name'),
        ];
        $tags = PhpDocumentor::tags()->with($customTags);

        $parser = new PhpdocParser($tags);
        $notations = $parser->parse($doc);
        $this->notations = $notations;
        $this->container = $container;
    }

    /**
     * @param object $object
     *
     * @return mixed
     */
    public function getter($object, string $property, array $args = [], $default = null)
    {
        $method = $this->getGetterName($property);

        if (method_exists($object, $method)) {
            $response = $object->$method(...$args);
        } elseif ($default instanceof Closure) {
            $response = $default($this->container);
        } else {
            $response = $default;
        }

        return $response;
    }

    public function getGetterName(string $property)
    {
        return $this->notations['properties'][$property]['getter'] ?? null;
    }

    public function hasGetter($property): bool
    {
        return isset($this->notations['properties'][$property]['getter']);
    }

    public function setter($object, $property, array $args = [], $default = null)
    {
        $method = $this->getSetterName($property);

        if (method_exists($object, $method)) {
            $response = $object->$method(...$args);
        } elseif ($default instanceof Closure) {
            $response = $default($this->container);
        } else {
            $response = $default;
        }

        return $response;
    }

    public function getSetterName(string $property)
    {
        return $this->notations['properties'][$property]['setter'] ?? null;
    }

    public function hasSetter($property): bool
    {
        return isset($this->notations['properties'][$property]['setter']);
    }
}