<?php

namespace Tests\Functional;

use Celtric\Fixtures\Fixtures;

abstract class AliceStyleFunctionalTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Fixtures
     */
    protected function fixtures()
    {
        return Fixtures::aliceStyle(__DIR__ . "/../fixtures/");
    }

    /**
     * @param string $fixtureIdentifier
     * @return mixed
     */
    protected function fixture($fixtureIdentifier)
    {
        return $this->fixtures()->fixture($fixtureIdentifier);
    }
}
