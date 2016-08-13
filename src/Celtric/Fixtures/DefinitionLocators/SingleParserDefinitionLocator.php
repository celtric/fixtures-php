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
    public function locate(FixtureIdentifier $fixtureIdentifier)
    {
        $rawData = $this->rawDataLocator->locate($fixtureIdentifier);

        $definitions = $this->parser->parse($rawData, $this);

        if (empty($definitions[$fixtureIdentifier->name()])) {
            throw new \RuntimeException("Could not find fixture \"{$fixtureIdentifier->toString()}\"");
        }

        return $definitions[$fixtureIdentifier->name()];
    }
}
