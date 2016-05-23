<?php

namespace Celtric\Fixtures\DefinitionLocators;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureIdentifier;
use Celtric\Fixtures\RawDataLocator;
use Celtric\Fixtures\RawDataParser;

final class SingleParserDefinitionLocator implements DefinitionLocator
{
    /** @var RawDataParser */
    private $parser;

    /** @var RawDataLocator */
    private $rawDataLocator;

    /**
     * @param RawDataLocator $rawDataLocator
     * @param RawDataParser $parser
     */
    public function __construct(RawDataLocator $rawDataLocator, RawDataParser $parser)
    {
        $this->parser = $parser;
        $this->rawDataLocator = $rawDataLocator;
    }

    /**
     * @inheritDoc
     */
    public function retrieveFixtureDefinition(FixtureIdentifier $fixtureIdentifier)
    {
        $definitions = $this->retrieveNamespaceDefinitions($fixtureIdentifier->getNamespace());

        if (empty($definitions[$fixtureIdentifier->name()])) {
            throw new \RuntimeException("Could not find fixture \"{$fixtureIdentifier->toString()}\"");
        }

        return $definitions[$fixtureIdentifier->name()];
    }

    /**
     * @inheritDoc
     */
    public function retrieveNamespaceDefinitions($namespace)
    {
        $rawData = $this->rawDataLocator->retrieveRawData($namespace);

        return $this->parser->parse($rawData);
    }
}
