<?php /** @noinspection ALL */

namespace Tests\Unit;

use NekoOs\Pood\Reflections\Classes;
use NekoOs\Pood\Reflections\Property;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionObject;
use stdClass;
use SomeClass;

class ClassesTest extends TestCase
{
    /**
     * @return array
     * @throws \ReflectionException
     */
    public function testGivenAnObjectOnFirstTimeWhenReflectThenShouldMakeAnObject()
    {
        $stub = $this->getMockBuilder(SomeClass::class)
            ->getMock();

        $reflection = Classes::reflect($stub);
        $this->assertInstanceOf(ReflectionObject::class, $reflection);

        return [
            $stub,
            $reflection
        ];
    }

    /**
     * @depends testGivenAnObjectOnFirstTimeWhenReflectThenShouldMakeAnObject
     *
     * @param $oneDepend
     */
    public function testGivenAnObjectOnTheLastTimeReflectedWhenReflectThenNotShouldReturnASingletonObject($oneDepend)
    {
        list($stub, $reflection) = $oneDepend;
        $this->assertNotSame($reflection, Classes::reflect($stub));
    }

    public function testGivenAClassNameOnTheFirstTimeReflectedWhenReflectThenShouldMakeAnObject()
    {
        $reflection = Classes::reflect(SomeClass::class);
        $this->assertInstanceOf(ReflectionClass::class, $reflection);

        return $reflection;
    }


    /**
     * @depends testGivenAClassNameOnTheFirstTimeReflectedWhenReflectThenShouldMakeAnObject
     *
     * @param $oneDepend
     *
     * @throws \ReflectionException
     */
    public function testGivenAClassNameInTheLastTimeReflectedWhenReflectThenNotShouldRemakeAnObject($oneDepend)
    {
        $this->assertSame($oneDepend, Classes::reflect(SomeClass::class));
    }
}
