<?php

namespace Tests\Functional;

use Celtric\Fixtures\FixtureDefinitionFactory;
use Celtric\Fixtures\Parsers\CeltricStyleParser;

abstract class CeltricStyleFunctionalTestCase extends SingleParserTestCase
{
    /**
     * @inheritdoc
     */
    protected function parser()
    {
        return new CeltricStyleParser(new FixtureDefinitionFactory());
    }
}
