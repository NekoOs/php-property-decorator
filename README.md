# Propertyable

Properties combine aspects of both fields and methods. To the user of an object, a property appears to be a field, accessing the property requires the same syntax. To the implementer of a class, a property is one or two code blocks, representing a get accessor and/or a set accessor.

## Installation

```sh
composer require nekoos-pood/propertyable
```

## Usage

### General use

Let's say you want to implement the use of properties to ensure that the value of some attribute requires an integer type:

```php
use NekoOs\Pood\Support\Contracts\Propertyable as PropertyableContract;
use NekoOs\Pood\Support\Traits\Propertyable;

class SomeClass implements PropertyableContract
{
    use Propertyable;

    public  $successAttribute;
    private $magicAttribute = 1024;
    private $failAttribute;

    public function getMagicAttribute() : int
    {
        return $this->magicAttribute;
    }

    public function setMagicAttribute(int $value)
    {
        $this->magicAttribute = $value;
    }
}

$someClass = new SomeClass();

## Setters
$someClass->successAttribute = 3000;      // set a value in public attribute normally
$someClass->magicAttribute   = 2048;      // set a value in attribute inaccessible through a property
$someClass->magicAttribute = 'fail';      // ¡throw error! must be of the type int, string given
$someClass->undefinedAttribute = $value;  // ¡throw error! undefined attribute
$someClass->failAttribute = $value;       // ¡throw error! unaccesible attribute

## Getters
$value = $someClass->successAttribute;    // return 3000
$value = $someClass->magicAttribute;      // return 1024
$value = $someClass->undefinedAttribute;  // ¡throw error! undefined attribute
$value = $someClass->failAttribute;       // ¡throw error! unaccesible attribute
```
