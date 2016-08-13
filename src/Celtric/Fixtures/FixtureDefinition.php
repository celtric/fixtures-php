<?php

namespace Celtric\Fixtures;

interface FixtureDefinition
{
    /**
     * @param DefinitionLocator $definitionLocator
     * @return mixed
     */
    public function instantiate(DefinitionLocator $definitionLocator);
}
