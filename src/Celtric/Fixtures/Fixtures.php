<?php

namespace Celtric\Fixtures;

final class Fixtures
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
     * @param string $fullFixtureName
     * @return mixed
     */
    public function fixture($fullFixtureName)
    {
        $fixtureIdentifier = new FixtureIdentifier($fullFixtureName);

        $definition = $this->definitionLocator->locate($fixtureIdentifier);

        return $this->convertDefinition($definition);
    }

    /**
     * @param FixtureDefinition $fixtureDefinition
     * @return array
     */
    private function convertDefinition(FixtureDefinition $fixtureDefinition)
    {
        if (!is_array($fixtureDefinition->data())) {
            if ($fixtureDefinition->isReference()) {
                $fixtureName = substr($fixtureDefinition->data(), 1);
                return $this->fixture($fixtureName);
            }

            return $fixtureDefinition->data();
        }

        $data = [];

        foreach ($fixtureDefinition->data() as $key => $value) {
            if (preg_match("/^(.*)<(.*)>$/", $key, $matches)) {
                $data[$matches[1]] = $this->convertDefinition(new FixtureDefinition($matches[2], $value));
            } else {
                $data[$key] = $this->convertDefinition(new FixtureDefinition("array", $value));
            }
        }

        return $this->castTo($fixtureDefinition->type(), $data);
    }

    /**
     * @param string $type
     * @param array $values
     * @return mixed
     */
    private function castTo($type, $values)
    {
        if ($type === "array") {
            return $values;
        }

        $instance = (new \ReflectionClass($type))->newInstanceWithoutConstructor();

        foreach ($values as $key => $value) {
            $reflectedProperty = new \ReflectionProperty($instance, $key);
            $reflectedProperty->setAccessible(true);
            $reflectedProperty->setValue($instance, $value);
        }

        return $instance;
    }
}
