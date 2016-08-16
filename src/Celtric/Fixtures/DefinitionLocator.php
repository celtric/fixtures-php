<?php
namespace Celtric\Fixtures;

interface DefinitionLocator
{
    /**
     * @param FixtureIdentifier $fixtureIdentifier
     * @return FixtureDefinition
     */
    public function fixtureDefinition(FixtureIdentifier $fixtureIdentifier);

    /**
     * @param string $namespace
     * @return FixtureDefinition[]
     */
    public function namespaceDefinitions($namespace);
}
