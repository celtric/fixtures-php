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
    public function loadFixture($fullFixtureName)
    {
        $fixtureIdentifier = new FixtureIdentifier($fullFixtureName);

        $definition = $this->definitionLocator->locate($fixtureIdentifier);

        return (new FixtureInstantiator($this->definitionLocator))->instantiate($definition);
    }

    /**
     * TODO: implement
     *
     * @param string $namespace
     * @return array
     */
    public function loadNamespace($namespace)
    {
        throw new \RuntimeException("Not implemented");
    }
}
