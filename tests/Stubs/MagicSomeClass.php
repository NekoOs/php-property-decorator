<?php

namespace Tests\Stubs;

class MagicSomeClass extends SomeClass
{

    public $successAttribute;

    public function __get($name)
    {

    }

    public function __isset($name)
    {

    }
}