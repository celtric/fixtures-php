<?php

namespace Tests\Functional;

use Celtric\Fixtures\Parsers\AliceStyleParser;

abstract class AliceStyleFunctionalTestCase extends FunctionalTestCase
{
    /**
     * @inheritdoc
     */
    protected function parser()
    {
        return new AliceStyleParser();
    }
}
