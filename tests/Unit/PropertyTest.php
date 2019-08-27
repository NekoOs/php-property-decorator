<?php

namespace Tests\Unit;

use NekoOs\Pood\Reflections\Property;
use NekoOs\Pood\Support\Contracts\ReadPropertyable;
use NekoOs\Pood\Support\Contracts\WritePropertyable;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Tests\Stubs\MagicSomeClass;
use Tests\Stubs\PropertyableSomeClass;
use Tests\Stubs\SomeClass;

class PropertyTest extends TestCase
{

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function testGivenReadPropertyableImplementedAndMutatorReadPropertyIsAccessibleWhenHasReadThenShouldReturnTrue()
    {
        // solved requirement to ReadPropertyable interface and is defined mutator method by undefined attribute
        $mock = $this->getMockBuilder(ReadPropertyable::class)
                ->setMethods(['getSuccessAttribute', '__isset', '__get'])
                ->getMock();

        $response = Property::hasRead($mock, 'successAttribute');

        $this->assertTrue($response);

        return $mock;
    }

    /**
     * @depends testGivenReadPropertyableImplementedAndMutatorReadPropertyIsAccessibleWhenHasReadThenShouldReturnTrue
     *
     * @param $mock
     *
     * @throws \ReflectionException
     */
    public function testGivenReadPropertyableImplementedAndMutatorReadPropertyIsInaccessibleWhenHasReadThenShouldReturnFalse($mock)
    {
        $response = Property::hasRead($mock, 'failAttribute');

        $this->assertFalse($response);
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function testGivenObjectWithAttributeAccessibleWhenHasReadThenShouldReturnTrue()
    {
        $mock = $this->createMock(SomeClass::class);

        $response = Property::hasRead($mock, 'successAttribute');

        $this->assertTrue($response);

        return $mock;
    }

    /**
     * @depends testGivenObjectWithAttributeAccessibleWhenHasReadThenShouldReturnTrue
     *
     * @param $mock
     *
     * @return string
     * @throws \ReflectionException
     */
    public function testGivenObjectWithAttributeInaccessibleWhenHasReadThenShouldReturnFalse($mock)
    {
        $response = Property::hasRead($mock, 'failAttribute');

        $this->assertFalse($response);
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function testGivenMagicObjectWithAttributeAccessibleWhenHasReadThenShouldReturnTrue()
    {
        $mock = $this->createMock(MagicSomeClass::class);

        $response = Property::hasRead(MagicSomeClass::class, 'successAttribute');

        $this->assertTrue($response);

        return $mock;
        
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function testGivenMagicObjectWithAttributeInaccessibleWhenHasReadThenShouldReturnFalse()
    {
        $mock = $this->createMock(MagicSomeClass::class);

        $response = Property::hasRead($mock, 'failAttribute');

        $this->assertFalse($response);

        return $mock;
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function testGivenMagicObjectWithMagicAttributeAccessibleWhenHasReadThenShouldReturnTrue()
    {
        $mock = $this->getMockBuilder(MagicSomeClass::class)
                ->setMethods(['__isset'])
                ->getMock();

        $mock->method('__isset')
                ->with('magicAttribute')
                ->willReturn(true);

        $response = Property::hasRead($mock, 'magicAttribute');

        $this->assertTrue($response);

        return $mock;
    }

    /**
     * @return string
     * @throws ReflectionException
     */
    public function testGivenReadPropertyableImplementedAndMutatorWritePropertyIsAccessibleWhenHasWriteThenShouldReturnTrue()
    {
        $mock = $this->getMockBuilder(WritePropertyable::class)
                ->setMethods(['setSuccessAttribute', '__set'])
                ->getMock();

        $response = Property::hasWrite($mock, 'successAttribute');

        $this->assertTrue($response);

        return $mock;
    }

    /**
     * @depends testGivenReadPropertyableImplementedAndMutatorWritePropertyIsAccessibleWhenHasWriteThenShouldReturnTrue
     *
     * @param $mock
     *
     * @throws \ReflectionException
     */
    public function testGivenReadPropertyableImplementedAndMutatorWritePropertyIsInaccessibleWhenHasWriteThenShouldReturnFalse($mock)
    {
        $response = Property::hasWrite($mock, 'failAttribute');

        $this->assertFalse($response);
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function testGivenObjectWithAttributeAccessibleWhenHasWriteThenShouldReturnTrue()
    {
        $mock = $this->createMock(SomeClass::class);

        $response = Property::hasWrite($mock, 'successAttribute');

        $this->assertTrue($response);

        return $mock;
    }

    /**
     * @depends testGivenObjectWithAttributeAccessibleWhenHasReadThenShouldReturnTrue
     *
     * @param $mock
     *
     * @return string
     * @throws \ReflectionException
     */
    public function testGivenObjectWithAttributeInaccessibleWhenHasWriteThenShouldReturnFalse($mock)
    {
        $response = Property::hasWrite($mock, 'failAttribute');

        $this->assertFalse($response);
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function testGivenMagicObjectWithAttributeAccessibleWhenHasWriteThenShouldReturnTrue()
    {
        $mock = $this->createMock(MagicSomeClass::class);

        $response = Property::hasWrite(MagicSomeClass::class, 'successAttribute');

        $this->assertTrue($response);

        return $mock;
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function testGivenMagicObjectWithAttributeInaccessibleWhenHasWriteThenShouldReturnFalse()
    {
        $mock = $this->createMock(MagicSomeClass::class);

        $response = Property::hasWrite($mock, 'failAttribute');

        $this->assertFalse($response);

        return $mock;
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function testGivenMagicObjectWithMagicAttributeAccessibleWhenHasWriteThenShouldReturnTrue()
    {
        $mock = $this->getMockBuilder(MagicSomeClass::class)
                ->setMethods(['__isset'])
                ->getMock();

        $mock->method('__isset')
                ->with('magicAttribute')
                ->willReturn(true);

        $response = Property::hasWrite($mock, 'magicAttribute');

        $this->assertTrue($response);

        return $mock;
    }

    public function testGivenPropertyableObjectWhenAccessToPropertyThenShouldReturnValue()
    {
        $mock = $this->getMockBuilder(PropertyableSomeClass::class)
                ->setMethods(['getMagicAttribute'])
                ->getMock();

        $mock->method('getMagicAttribute')
                ->willReturn(true);

        $this->assertTrue($mock->magicAttribute);
    }

}
