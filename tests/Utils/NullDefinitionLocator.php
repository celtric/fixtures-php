<?php

namespace Tests\Utils;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureIdentifier;

final class NullDefinitionLocator implements DefinitionLocator
{
    /**
     * @inheritDoc
     */
    public function locate(FixtureIdentifier $fixtureIdentifier)
    {
        throw new \RuntimeException("Not implemented");
    }
}
