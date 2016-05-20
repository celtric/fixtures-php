<?php

namespace Tests\Functional;

use Celtric\Fixtures\Fixtures;

abstract class FunctionalTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $fixtureIdentifier
     * @return mixed
     */
    protected function fixture($fixtureIdentifier)
    {
        return (new Fixtures(__DIR__ . "/../fixtures/"))->fixture($fixtureIdentifier);
    }
}