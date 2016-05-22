<?php

namespace Tests\Functional;

use Celtric\Fixtures\Parsers\AliceStyleParser;

abstract class AliceStyleFunctionalTestCase extends SingleParserTestCase
{
    /**
     * @inheritdoc
     */
    protected function parser()
    {
        return new AliceStyleParser();
    }
}
