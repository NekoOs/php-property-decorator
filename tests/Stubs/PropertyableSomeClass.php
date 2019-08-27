<?php

namespace Tests\Stubs;

use NekoOs\Pood\Support\Contracts\Propertyable as PropertyableContract;
use NekoOs\Pood\Support\Traits\Propertyable;

class PropertyableSomeClass extends SomeClass implements PropertyableContract
{

    use Propertyable;
}
