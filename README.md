Celtric Fixtures - PHP
======================

[![Build Status](https://travis-ci.org/celtric/fixtures-php.svg?branch=master)](https://travis-ci.org/celtric/fixtures-php)

Introduction
------------

This is a small library that aims to offer an easy way to define static fixtures. By default it uses YAML files and a custom format (inspired by [Alice](https://github.com/nelmio/alice)), but tries to give several extension points so any developer can easily customize it.

### What this library is not

The aim of this package is *not* to provide dynamic fixtures (via fake data generation). Support for it can be added, but you are encouraged to check out [Alice](https://github.com/nelmio/alice) or any other fixture generator.

An example
----------

We are going to assume that we have the following class definition:

```php
<?php

namespace Foo\Bar;

class Person
{
    private $name;
    private $age;

    public function __construct($name, $age)
    {
        $this->name = $name;
        $this->age = $age;
    }
}
```

And the following fixtures file: **`fixtures/people.yml`**

```yaml
root_type: Foo\Bar\Person

ricard:
    name: Ricard
    age: 30

laura:
    name: Laura
    age: 29
```

We are now ready to use the library as follows:

```php
<?php

namespace Tests;

class FooTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function can_load__fixtures()
    {
        $this->assertEquals(new Person("Ricard", 30), $this->fixture("people.ricard"));
    }
    
    /**
     * @param string $fixtureName
     * @return mixed
     */
    private function fixture($fixtureName)
    {
        $fixtures = Fixtures::celtricStyle(__DIR__ . "/../fixtures/");
        
        return $fixtures->fixture($fixtureName);
    }
}
```

### A more complex example

We now have an slightly more complex class definition:

```php
<?php

namespace Foo\Bar;

class Money
{
    private $amount;
    private $currency;

    public function __construct($amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }
}

class Currency
{
    private $isoCode;

    public function __construct($isoCode)
    {
        $this->isoCode = $isoCode;
    }
}
```

In order to define fixtures for it, we can use the following example: **`fixtures/money.yml`**


```yaml
root_type: Foo\Bar\Money

# We can inline the currency definition
two_euro:
    amount: 200
    currency<Foo\Bar\Currency>:
        isoCode: EUR

# Or we can reference it
three_euro:
    amount: 300
    currency: "@money.euro"

euro<Foo\Bar\Currency>:
    isoCode: EUR
```

Development status
------------------

This library is currently in beta and any feedback will be welcomed.
