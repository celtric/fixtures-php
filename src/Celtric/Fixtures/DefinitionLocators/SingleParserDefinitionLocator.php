<?php

namespace Celtric\Fixtures\DefinitionLocators;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureIdentifier;
use Celtric\Fixtures\RawDataLocator;
use Celtric\Fixtures\RawDataParser;

final class SingleParserDefinitionLocator implements DefinitionLocator
{
    /** @var RawDataLocator */
    private $rawDataLocator;

    /** @var RawDataParser */
    private $parser;

    /**
     * @param RawDataLocator $rawDataLocator
     * @param RawDataParser $parser
     */
    public function __construct(RawDataLocator $rawDataLocator, RawDataParser $parser)
    {
        $this->rawDataLocator = $rawDataLocator;
        $this->parser = $parser;
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
