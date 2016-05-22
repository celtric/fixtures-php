<?php

namespace Celtric\Fixtures;

interface RawDataLocator
{
    /**
     * @param FixtureIdentifier $fixtureIdentifier
     * @return array
     */
    public function locate(FixtureIdentifier $fixtureIdentifier);
}
