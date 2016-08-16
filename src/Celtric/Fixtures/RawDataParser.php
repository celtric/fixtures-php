<?php

namespace Celtric\Fixtures;

interface RawDataParser
{
    /**
     * @param array $rawData
     * @param DefinitionLocator $definitionLocator
     * @return FixtureDefinition[]
     */
    public function parse(array $rawData, DefinitionLocator $definitionLocator);
}
