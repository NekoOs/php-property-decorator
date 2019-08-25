<?php

namespace Tests\Unit;

use NekoOs\Pood\Support\Contracts\ReadPropertyable;
use NekoOs\Pood\Reflections\Property;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\MagicSomeClass;
use Tests\Stubs\SomeClass;

class PropertyTest extends TestCase
{

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function testGivenReadPropertyableImplementedAndMutatorReadPropertyIsAccessibleWhenHasReadThenShouldReturnTrue()
    {
        $mock = $this->getMockBuilder(ReadPropertyable::class)
            ->setMethods(['getSuccessAttribute', '__isset','__get'])
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
}
