<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\DefinitionLocator;
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
    public function instantiate(DefinitionLocator $definitionLocator)
    {
        $instance = (new \ReflectionClass($this->className))->newInstanceWithoutConstructor();

        foreach ($this->properties as $key => $value) {
            if ($value instanceof MethodCallFixture) {
                $arguments = $value->instantiate($definitionLocator);
                call_user_func_array([$instance, $key], $arguments);
                continue;
            }

            $reflectedProperty = new \ReflectionProperty($instance, $key);
            $reflectedProperty->setAccessible(true);
            $reflectedProperty->setValue($instance, $value->instantiate($definitionLocator));
        }

        return $instance;
    }
}
