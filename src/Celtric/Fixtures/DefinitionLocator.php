<?php
namespace Celtric\Fixtures;

interface DefinitionLocator
{
    /**
     * @param FixtureIdentifier $fixtureIdentifier
     * @return FixtureDefinition
     */
    public function locate(FixtureIdentifier $fixtureIdentifier);
}
