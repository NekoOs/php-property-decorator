# PHP Property Decorator

Properties combine aspects of both fields and methods. To the user of an object, a property appears to be a field, accessing the property requires the same syntax. To the implementer of a class, a property is one or two code blocks, representing a get accessor and/or a set accessor.

## Installation

```sh
composer require "nekoos/php-property-decorator:dev-master"
```

## Usage

### General use

This library provides your with a built-in @property decorator as DocBlock which makes use of getter and setters much easier in
Object-Oriented Programming.

```php
use NekoOs\Decorator\Concerns\HasDecoratorProperties;

require_once "vendor/autoload.php";
/**
 * @property int $age {read getAge} {write setAge}
 */
class SomeClass
{
    use HasDecoratorProperties;

    private $age;

    public function getAge() : int
    {
        return $this->age;
    }

    public function setAge(int $value): void
    {
        $this->age= $value;
    }
}

$someClass = new SomeClass();

$someClass->age = '33';

var_dump($someClass->age);      # int(33)