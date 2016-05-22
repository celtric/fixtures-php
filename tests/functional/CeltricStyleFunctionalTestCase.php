<?php

namespace Tests\Functional;

use Celtric\Fixtures\Parsers\CeltricStyleParser;

abstract class CeltricStyleFunctionalTestCase extends SingleParserTestCase
{
    /**
     * @inheritdoc
     */
    protected function parser()
    {
        return new CeltricStyleParser();
    }
}
