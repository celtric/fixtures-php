<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureDefinition;
use Celtric\Fixtures\FixtureIdentifier;

final class ReferenceFixture implements FixtureDefinition
{
    /** @var FixtureIdentifier */
    private $reference;

    /** @var DefinitionLocator */
    private $definitionLocator;

    /**
     * @param FixtureIdentifier $reference
     * @param DefinitionLocator $definitionLocator
     */
    public function __construct(FixtureIdentifier $reference, DefinitionLocator $definitionLocator)
    {
        $this->reference = $reference;
        $this->definitionLocator = $definitionLocator;
    }

    /**
     * @inheritDoc
     */
    public function instantiate()
    {
        if (strpos($this->reference->toString(), "->") === false) {
            return $this->definitionLocator->fixtureDefinition($this->reference)->instantiate();
        } else {
            return $this->instantiateProperty();
        }
    }

    /**
     * @return mixed
     */
    private function instantiateProperty()
    {
        list($reference, $propertyName) = explode("->", $this->reference->toString());

        $instance = $this->definitionLocator->fixtureDefinition(new FixtureIdentifier($reference))->instantiate();

        switch (gettype($instance)) {
            case "object":
                return $this->getObjectProperty($instance, $propertyName);
            case "array":
                return $instance[$propertyName];
            default:
                throw new \RuntimeException("Unable to extract property");
        }
    }

    /**
     * @param mixed $object
     * @param string $propertyName
     * @return mixed
     */
    private function getObjectProperty($object, $propertyName)
    {
        $property = new \ReflectionProperty($object, $propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * @inheritDoc
     */
    function __sleep()
    {
        return ['reference'];
    }
}
