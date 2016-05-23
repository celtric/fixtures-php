<?php

namespace Tests\Functional;

use Celtric\Fixtures\DefinitionLocators\SingleParserDefinitionLocator;
use Celtric\Fixtures\RawDataLocators\YAMLRawDataLocator;
use Celtric\Fixtures\Fixtures;
use Celtric\Fixtures\RawDataParser;

abstract class SingleParserTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $fixtureIdentifier
     * @return mixed
     */
    protected function loadFixture($fixtureIdentifier)
    {
        return $this->fixtures()->fixture($fixtureIdentifier);
    }

    /**
     * @param string $namespace
     * @return array
     */
    protected function loadNamespace($namespace)
    {
        return $this->fixtures()->namespaceFixtures($namespace);
    }

    /**
     * @return Fixtures
     */
    protected function fixtures()
    {
        return new Fixtures(
                new SingleParserDefinitionLocator(
                        new YAMLRawDataLocator(__DIR__ . "/../fixtures/"),
                        $this->parser()));
    }

    /**
     * @return RawDataParser
     */
    abstract protected function parser();
}
