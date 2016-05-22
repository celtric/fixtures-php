<?php

namespace Celtric\Fixtures;

interface RawDataParser
{
    /**
     * @param array $rawData
     * @return FixtureDefinition[]
     */
    public function parse(array $rawData);
}
