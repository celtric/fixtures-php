<?php

namespace Celtric\Fixtures;

final class FixtureDefinition
{
    /** @var string */
    private $type;

    /** @var mixed */
    private $data;

    /**
     * @param string $type
     * @param mixed $data
     */
    public function __construct($type, $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function isReference()
    {
        return $this->type === "reference";
    }

    /**
     * @return bool
     */
    public function isNativeValue()
    {
        return !$this->isReference() && (is_scalar($this->data) || is_null($this->data));
    }

    /**
     * @return bool
     */
    public function isArray()
    {
        return $this->type === "array";
    }
}
