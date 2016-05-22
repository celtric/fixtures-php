<?php

namespace Celtric\Fixtures;

final class FixtureInstantiator
{
    /** @var DefinitionLocator */
    private $definitionLocator;

    /**
     * @param DefinitionLocator $definitionLocator
     */
    public function __construct(DefinitionLocator $definitionLocator)
    {
        $this->definitionLocator = $definitionLocator;
    }


    /**
     * @param FixtureDefinition $fixtureDefinition
     * @return mixed
     */
    public function instantiate(FixtureDefinition $fixtureDefinition)
    {
        switch (true) {
            case $fixtureDefinition->isNativeValue():
                return $this->instantiateNativeValue($fixtureDefinition);
            case $fixtureDefinition->isArray():
                return $this->instantiateArray($fixtureDefinition);
            case $fixtureDefinition->isReference():
                return $this->instantiateReference($fixtureDefinition);
            default:
                return $this->instantiateObject($fixtureDefinition);
        }
    }

    /**
     * @param FixtureDefinition $fixtureDefinition
     * @return int|float|string|bool|null
     */
    private function instantiateNativeValue(FixtureDefinition $fixtureDefinition)
    {
        return $fixtureDefinition->data();
    }

    /**
     * @param FixtureDefinition $fixtureDefinition
     * @return array
     */
    private function instantiateArray(FixtureDefinition $fixtureDefinition)
    {
        $instantiatedData = [];

        foreach ($fixtureDefinition->data() as $key => $value) {
            $instantiatedData[$key] = $this->instantiate($value);
        }

        return $instantiatedData;
    }

    /**
     * @param FixtureDefinition $fixtureDefinition
     * @return mixed
     */
    private function instantiateReference(FixtureDefinition $fixtureDefinition)
    {
        return $this->instantiate($this->definitionLocator->locate(new FixtureIdentifier($fixtureDefinition->data())));
    }

    /**
     * @param FixtureDefinition $fixtureDefinition
     * @return mixed
     */
    private function instantiateObject(FixtureDefinition $fixtureDefinition)
    {
        $instance = (new \ReflectionClass($fixtureDefinition->type()))->newInstanceWithoutConstructor();

        foreach ($fixtureDefinition->data() as $key => $value) {
            $reflectedProperty = new \ReflectionProperty($instance, $key);
            $reflectedProperty->setAccessible(true);
            $reflectedProperty->setValue($instance, $this->instantiate($value));
        }

        return $instance;
    }
}