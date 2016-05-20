<?php

namespace Tests\Utils;

final class Person
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var int */
    private $age;

    /** @var Person|null */
    private $friend;

    /**
     * @param string $name
     * @param int $age
     */
    public function __construct($name, $age)
    {
        $this->name = $name;
        $this->age = $age;
    }

    /**
     * @param Person $friend
     */
    public function setFriend(Person $friend)
    {
        $this->friend = $friend;
    }
}
