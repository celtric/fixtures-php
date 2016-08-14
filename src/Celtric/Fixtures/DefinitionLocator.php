<?php
namespace Celtric\Fixtures;

interface DefinitionLocator
{
    /**
     * @param FixtureIdentifier $fixtureIdentifier
     * @return FixtureDefinition
     */
    public function retrieveFixtureDefinition(FixtureIdentifier $fixtureIdentifier);

    /**
     * @param string $namespace
     * @return FixtureDefinition[]
     */
    public function retrieveNamespaceDefinitions($namespace);
}
