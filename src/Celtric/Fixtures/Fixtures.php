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

        $definition = $this->definitionLocator->retrieveFixtureDefinition($fixtureIdentifier);

        return $this->instantiate($definition);
    }

    /**
     * @param string $namespace
     * @return array
     */
    public function namespaceFixtures($namespace)
    {
        $definitions = $this->definitionLocator->retrieveNamespaceDefinitions($namespace);

        return array_map([$this, "instantiate"], $definitions);
    }

    /**
     * @param $definition
     * @return mixed
     */
    private function instantiate($definition)
    {
        return (new FixtureInstantiator($this->definitionLocator))->instantiate($definition);
    }
}
