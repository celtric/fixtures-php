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
    private function __construct($type, $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * @param string $type
     * @param mixed $data
     * @return FixtureDefinition
     */
    public static function generic($type, $data)
    {
        return new self($type, $data);
    }

    /**
     * @param mixed $value
     * @return FixtureDefinition
     */
    public static function native($value)
    {
        $type = strtolower(gettype($value));
        if ($type === "double") {
            $type = "float";
        }
        return new self($type, $value);
    }

    /**
     * @param array $array
     * @return FixtureDefinition
     */
    public static function arr(array $array)
    {
        return new self("array", $array);
    }

    /**
     * @param string $className
     * @param array $properties
     * @return FixtureDefinition
     */
    public static function object($className, array $properties)
    {
        return new self($className, $properties);
    }

    /**
     * @param string $reference
     * @return FixtureDefinition
     */
    public static function reference($reference)
    {
        return new self("reference", $reference);
    }

    /**
     * @param array $args
     * @return FixtureDefinition
     */
    public static function methodCall(array $args)
    {
        return new self("method_call", $args);
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

    /**
     * @return bool
     */
    public function isMethodCall()
    {
        return $this->type === "method_call";
    }
}
