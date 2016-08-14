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

        return $this->definitionLocator->fixtureDefinition($fixtureIdentifier)->instantiate();
    }

    /**
     * @param string $namespace
     * @return array
     */
    public function namespaceFixtures($namespace)
    {
        $definitions = $this->definitionLocator->namespaceDefinitions($namespace);

        return array_map(function (FixtureDefinition $d) { return $d->instantiate(); }, $definitions);
    }
}
