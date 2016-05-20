<?php

namespace Tests\Utils;

final class Person
{
    /** @var string */
    private $name;

    /** @var int */
    private $age;

    /**
     * @param string $name
     * @param int $age
     */
    public function __construct($name, $age)
    {
        $this->name = $name;
        $this->age = $age;
    }
}
