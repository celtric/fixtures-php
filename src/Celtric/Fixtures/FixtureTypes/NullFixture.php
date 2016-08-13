<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureDefinition;

final class NullFixture implements FixtureDefinition
{
    /**
     * @inheritDoc
     */
    public function instantiate(DefinitionLocator $definitionLocator)
    {
        return null;
    }
}
