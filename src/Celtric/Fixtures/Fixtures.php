<?php

namespace Celtric\Fixtures;

use Celtric\Fixtures\DefinitionLocators\LocatorCacher;
use Celtric\Fixtures\DefinitionLocators\SingleParserDefinitionLocator;
use Celtric\Fixtures\Parsers\AliceStyleParser;
use Celtric\Fixtures\Parsers\CeltricStyleParser;
use Celtric\Fixtures\RawDataLocators\YAMLRawDataLocator;

final class Fixtures
{
    /** @var DefinitionLocator */
    private $definitionLocator;

    /**
     * @param DefinitionLocator $definitionLocator
     */
    public function __construct(DefinitionLocator $definitionLocator)
    {
        $this->definitionLocator = $definitionLocator;
    }

    /**
     * @param string $fixturesPath
     * @return Fixtures
     */
    public static function celtricStyle($fixturesPath)
    {
        $definitionLocator = new SingleParserDefinitionLocator(
                new YAMLRawDataLocator($fixturesPath),
                new CeltricStyleParser(new CachedDefinitionFactory()));

        return new self(new LocatorCacher($definitionLocator));
    }

    /**
     * @param string $fixturesPath
     * @return Fixtures
     */
    public static function aliceStyle($fixturesPath)
    {
        $definitionLocator = new SingleParserDefinitionLocator(
                new YAMLRawDataLocator($fixturesPath),
                new AliceStyleParser(new CachedDefinitionFactory()));

        return new self(new LocatorCacher($definitionLocator));
    }

    /**
     * @param string $fullFixtureName
     * @return mixed
     */
    public function fixture($fullFixtureName)
    {
        $fixtureIdentifier = new FixtureIdentifier($fullFixtureName);

        return $this->definitionLocator->fixtureDefinition($fixtureIdentifier)->instantiate();
    }

    /**
     * @param string $namespace
     * @return array
     */
    public function namespaceFixtures($namespace)
    {
        $definitions = $this->definitionLocator->namespaceDefinitions($namespace);

        return array_map(function (FixtureDefinition $d) { return $d->instantiate(); }, $definitions);
    }
}
