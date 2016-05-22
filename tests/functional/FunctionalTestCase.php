<?php

namespace Tests\Functional;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\Locators\YAMLRawDataLocator;
use Celtric\Fixtures\Fixtures;
use Celtric\Fixtures\RawDataParser;

abstract class FunctionalTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $fixtureIdentifier
     * @return mixed
     */
    protected function fixture($fixtureIdentifier)
    {
        $definitionLocator = new DefinitionLocator(
                new YAMLRawDataLocator(__DIR__ . "/../fixtures/"),
                $this->parser());

        return (new Fixtures($definitionLocator))->fixture($fixtureIdentifier);
    }

    /**
     * @return RawDataParser
     */
    abstract protected function parser();
}
