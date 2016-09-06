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

    /** @var int */
    private $x;

    /** @var int */
    private $y;

    /** @var int */
    private $z;

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
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param Person $friend
     */
    public function setFriend(Person $friend)
    {
        $this->friend = $friend;
    }

    /**
     * @return Person|null
     */
    public function friend()
    {
        return $this->friend;
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $z
     */
    public function setCoordinates($x, $y, $z)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }
}
