<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureDefinition;
use Celtric\Fixtures\FixtureIdentifier;

final class ReferenceFixture extends FixtureDefinition
{
    /**
     * @inheritDoc
     */
    public function instantiate(DefinitionLocator $definitionLocator)
    {
        return $definitionLocator->locate(new FixtureIdentifier($this->data()))->instantiate($definitionLocator);
    }
}
