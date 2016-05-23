<?php

namespace Celtric\Fixtures;

final class FixtureIdentifier
{
    /** @var string */
    private $stringRepresentation;

    /** @var string */
    private $namespace;

    /** @var string */
    private $name;

    /**
     * @param string $stringRepresentation
     */
    public function __construct($stringRepresentation)
    {
        $this->stringRepresentation = $stringRepresentation;
        $this->name = array_reverse(explode(".", $stringRepresentation))[0];
        $this->namespace = substr($stringRepresentation, 0, strlen($stringRepresentation) - strlen($this->name) - 1);
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Prefixed with "get" because namespace is a reserved word.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->stringRepresentation;
    }
}
