<?php

namespace Tests\Functional;

use Celtric\Fixtures\Parsers\CeltricStyleParser;

abstract class CeltricStyleFunctionalTestCase extends FunctionalTestCase
{
    /**
     * @inheritdoc
     */
    protected function parser()
    {
        return new CeltricStyleParser();
    }
}
