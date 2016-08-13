<?php

namespace Celtric\Fixtures;

interface FixtureDefinition
{
    /**
     * @return mixed
     */
    public function instantiate();
}
