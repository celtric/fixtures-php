<?php

namespace Celtric\Fixtures\DefinitionLocators;

use Celtric\Fixtures\FixtureDefinition;

interface FileParser
{
    /**
     * @param string $fileContent
     * @return FixtureDefinition[]
     */
    public function parse($fileContent);
}
