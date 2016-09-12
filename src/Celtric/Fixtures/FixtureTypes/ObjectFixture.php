<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\FixtureDefinition;

final class ObjectFixture implements FixtureDefinition
{
    /** @var string */
    private $className;

    /** @var FixtureDefinition[] */
    private $properties;

    /**
     * @param string $className
     * @param FixtureDefinition[] $properties
     */
    public function __construct($className, array $properties)
    {
        $this->className = $className;
        $this->properties = $properties;
    }

    /**
     * @inheritDoc
     */
    public function instantiate()
    {
        $hasConstructor = array_key_exists("__construct", $this->properties);

        if ($hasConstructor) {
            $instance = (new \ReflectionClass($this->className))->newInstance($this->properties["__construct"]);
        } else {
            $instance = (new \ReflectionClass($this->className))->newInstanceWithoutConstructor();
        }

        foreach ($this->properties as $key => $value) {
            $isConstructor = $key === "__construct";

            if ($isConstructor) {
                continue;
            }

            $isMethodCall = method_exists($instance, $key);

            if ($isMethodCall) {
                $arguments = $value->instantiate();
                call_user_func_array([$instance, $key], $arguments);
                continue;
            }

            $reflectedProperty = new \ReflectionProperty($instance, $key);
            $reflectedProperty->setAccessible(true);
            $reflectedProperty->setValue($instance, $value->instantiate());
        }

        return $instance;
    }
}
