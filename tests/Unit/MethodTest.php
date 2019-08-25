<?php

namespace Tests\Unit;

use NekoOs\Pood\Reflections\Method;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\SomeClass;

class MethodTest extends TestCase
{

    public function testGivenSuccessPublicMethodNameWhenIsPublicThenShouldReturnTrue()
    {
        $this->assertTrue(Method::isPublic(SomeClass::class, 'doSomething'));
    }

    public function testGivenFailPublicMethodNameWhenIsPublicThenShouldReturnTrue()
    {
        $this->assertFalse(Method::isPublic(SomeClass::class, 'dontSomething'));
    }
}
