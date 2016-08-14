<?php

namespace Tests\Functional;

use Celtric\Fixtures\DefinitionLocators\SingleParserDefinitionLocator;
use Celtric\Fixtures\RawDataLocators\YAMLRawDataLocator;
use Celtric\Fixtures\Fixtures;
use Celtric\Fixtures\RawDataParser;

abstract class SingleParserTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Fixtures
     */
    protected function fixtures()
    {
        $definitionLocator = new SingleParserDefinitionLocator(
                new YAMLRawDataLocator(__DIR__ . "/../fixtures/"),
                $this->parser());

        return new Fixtures($definitionLocator);
    }

    /**
     * @param string $fixtureIdentifier
     * @return mixed
     */
    protected function fixture($fixtureIdentifier)
    {
        return $this->fixtures()->fixture($fixtureIdentifier);
    }

    /**
     * @return RawDataParser
     */
    abstract protected function parser();
}
