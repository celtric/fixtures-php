<?php

namespace Tests\Utils;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureIdentifier;

final class NullDefinitionLocator implements DefinitionLocator
{
    /**
     * @inheritDoc
     */
    public function fixtureDefinition(FixtureIdentifier $fixtureIdentifier)
    {
        throw new \RuntimeException("Not implemented");
    }

    /**
     * @inheritDoc
     */
    public function namespaceDefinitions($namespace)
    {
        throw new \RuntimeException("Not implemented");
    }
}
