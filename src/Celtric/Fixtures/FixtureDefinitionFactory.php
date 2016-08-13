<?php

namespace Celtric\Fixtures;

use Celtric\Fixtures\FixtureTypes\ArrayFixture;
use Celtric\Fixtures\FixtureTypes\MethodCallFixture;
use Celtric\Fixtures\FixtureTypes\NativeValueFixture;
use Celtric\Fixtures\FixtureTypes\ObjectFixture;
use Celtric\Fixtures\FixtureTypes\ReferenceFixture;

class FixtureDefinitionFactory
{
    /**
     * @param string $type
     * @param mixed $data
     * @return FixtureDefinition
     * @deprecated
     */
    public function generic($type, $data)
    {
        switch ($type) {
            case "integer":
            case "float":
            case "boolean":
            case "string":
            case "null":
                return $this->native($data);
            case "array":
                return $this->arr($data);
            case "reference":
                return $this->reference($data);
            case "method_call":
                return $this->methodCall($data);
            default:
                return $this->object($type, $data);
        }
    }

    /**
     * @param mixed $value
     * @return FixtureDefinition
     */
    public function native($value)
    {
        return new NativeValueFixture($value);
    }

    /**
     * @param array $data
     * @return FixtureDefinition
     */
    public function arr(array $data)
    {
        return new ArrayFixture($data);
    }

    /**
     * @param string $className
     * @param array $properties
     * @return FixtureDefinition
     */
    public function object($className, array $properties)
    {
        return new ObjectFixture($className, $properties);
    }

    /**
     * @param string $reference
     * @return FixtureDefinition
     */
    public function reference($reference)
    {
        return new ReferenceFixture($reference);
    }

    /**
     * @param array $args
     * @return FixtureDefinition
     */
    public function methodCall(array $args)
    {
        return new MethodCallFixture($args);
    }
}
