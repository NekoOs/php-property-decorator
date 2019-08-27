<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\Stubs\PropertyableSomeClass;

class PropertyableTest extends TestCase
{

    public function testGivenPropertyableObjectWhenPublicAttributeIsSetThenNoMethodShouldBeExecuted()
    {
        $mock = $this->getMockBuilder(PropertyableSomeClass::class)
                ->getMock();
        $mock->expects($this->never())
                ->method($this->anything());

        $mock->successAttribute = 3000;

        return $mock;
    }

    /**
     * @depends testGivenPropertyableObjectWhenPublicAttributeIsSetThenNoMethodShouldBeExecuted
     * 
     * @param object $mock
     */
    public function testGivenPropertyableObjectWhenPublicAttributeIsGetThenNoMethodShouldBeExecuted($mock)
    {
        $mock->successAttribute;
    }

    public function testGivenPropertyableObjectWhenMagicAttributeIsSetThenShouldMethodExecuted()
    {
        $mock = $this->getMockBuilder(PropertyableSomeClass::class)
                ->setMethods(['setMagicAttribute'])
                ->getMock();
        $mock->expects($this->once())
                ->method('setMagicAttribute')
                ->willReturn(null);

        $mock->magicAttribute = 3000;

        return $mock;
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessageRegExp /Argument 1 passed to .+ must be of the type integer, string given/
     */
    public function testGivenPropertyableObjectWhenMagicAttributeIsFailSetThenShouldMethodExecuted()
    {
        $mock = $this->getMockBuilder(PropertyableSomeClass::class)
                ->setMethods(['setMagicAttribute'])
                ->getMock();
        $mock->expects($this->once())
                ->method('setMagicAttribute')
                ->willReturnCallback(function (int $value) {
                    unset($value);
                });

        $mock->magicAttribute = 'fail';
    }

    /**
     * @expectedException \NekoOs\Pood\Exceptions\UndefinedAttributeException
     * @expectedExceptionMessageRegExp /^Undefined property: \S+$/
     */
    public function testGivenPropertyableObjectWhenUndefinedAttributeIsSetThenShouldMethodExecuted()
    {
        $mock = $this->getMockBuilder(PropertyableSomeClass::class)
                ->setMethodsExcept(['__set'])
                ->getMock();
        $mock->undefinedAttribute = 'value';
    }
    
    /**
     * @expectedException \NekoOs\Pood\Exceptions\UndefinedAttributeException
     * @expectedExceptionMessageRegExp /^Undefined property: \S+$/
     */
    public function testGivenPropertyableObjectWhenUndefinedAttributeIsGetThenShouldMethodExecuted()
    {
        $mock = $this->getMockBuilder(PropertyableSomeClass::class)
                ->setMethodsExcept(['__get'])
                ->getMock();
        $mock->undefinedAttribute;
    }
    
    /**
     * @group puto
     * 
     * @expectedException \NekoOs\Pood\Exceptions\AccessAttributeException
     * @expectedExceptionMessageRegExp /^Cannot access (private|protected) property: \S+$/
     */
    public function testGivenPropertyableObjectWhenInaccesibleAttributeIsSetThenShouldMethodExecuted()
    {
        $mock = $this->getMockBuilder(PropertyableSomeClass::class)
                ->setMethodsExcept(['__set'])
                ->getMock();
        $mock->failAttribute = 'value';
    }
    
    
    /**
     * @group puto
     * 
     * @expectedException \NekoOs\Pood\Exceptions\AccessAttributeException
     * @expectedExceptionMessageRegExp /^Cannot access (private|protected) property: \S+$/
     */
    public function testGivenPropertyableObjectWhenInaccesibleAttributeIsGetThenShouldMethodExecuted()
    {
        $mock = $this->getMockBuilder(PropertyableSomeClass::class)
                ->setMethodsExcept(['__get'])
                ->getMock();
        $mock->failAttribute;
    }

    public function testGivenPropertyableObjectWhenMagicAttributeIsGetThenShouldMethodExecuted()
    {
        $mock = $this->getMockBuilder(PropertyableSomeClass::class)
                ->setMethods(['getMagicAttribute'])
                ->getMock();
        $mock->expects($this->once())
                ->method('getMagicAttribute')
                ->willReturn(null);

        $mock->magicAttribute;
    }

}
