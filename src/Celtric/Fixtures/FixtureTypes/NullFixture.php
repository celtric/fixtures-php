<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\FixtureDefinition;

final class NullFixture implements FixtureDefinition
{
    /**
     * @inheritDoc
     */
    public function instantiate()
    {
        return null;
    }
}
