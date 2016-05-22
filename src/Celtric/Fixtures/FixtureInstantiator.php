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
        if ($fixtureDefinition->isNativeValue()) {
            return $fixtureDefinition->data();
        }

        if ($fixtureDefinition->isArray()) {
            $instantiatedData = [];

            foreach ($fixtureDefinition->data() as $key => $value) {
                $instantiatedData[$key] = $this->instantiate($value);
            }

            return $instantiatedData;
        }

        if ($fixtureDefinition->isReference()) {
            return $this->instantiate(
                    $this->definitionLocator->locate(new FixtureIdentifier($fixtureDefinition->data())));
        }

        $instance = (new \ReflectionClass($fixtureDefinition->type()))->newInstanceWithoutConstructor();

        foreach ($fixtureDefinition->data() as $key => $value) {
            $reflectedProperty = new \ReflectionProperty($instance, $key);
            $reflectedProperty->setAccessible(true);
            $reflectedProperty->setValue($instance, $this->instantiate($value));
        }

        return $instance;
    }
}
