# Propertyable

Properties combine aspects of both fields and methods. To the user of an object, a property appears to be a field, accessing the property requires the same syntax. To the implementer of a class, a property is one or two code blocks, representing a get accessor and/or a set accessor.

## Installation

```php
composer require nekoos-pood/propertyable
```

## Usage

### General use

Let's say you want to implement the use of properties to ensure that the value of some attribute requires an integer type:

```php
class SomeClass {
    public  $successAttribute;
    private $magicAttribute = 1024;

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

# $someClass->successAttribute = 3000;   set a public attribute normally
# $someClass->magicAttribute   = 2048;   set an attribute accessible through a property
# $someClass->successAttribute;          => 3000
# $someClass->magicAttribute;            => 1024
# $someClass->magicAttribute   = 'fail'; ¡throw error! must be of the type int, string given
```
