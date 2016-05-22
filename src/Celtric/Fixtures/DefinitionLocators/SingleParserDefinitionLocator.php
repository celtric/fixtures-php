<?php

namespace Celtric\Fixtures\DefinitionLocators;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureIdentifier;
use Celtric\Fixtures\RawDataLocator;
use Celtric\Fixtures\RawDataParser;

final class SingleParserDefinitionLocator implements DefinitionLocator
{
    /** @var RawDataParser */
    private $fileParser;

    /** @var RawDataLocator */
    private $rawDataLocator;

    /**
     * @param RawDataLocator $rawDataLocator
     * @param RawDataParser $fileParser
     */
    public function __construct(RawDataLocator $rawDataLocator, RawDataParser $fileParser)
    {
        $this->fileParser = $fileParser;
        $this->rawDataLocator = $rawDataLocator;
    }

    /**
     * @inheritDoc
     */
    public function locate(FixtureIdentifier $fixtureIdentifier)
    {
        $rawData = $this->rawDataLocator->locate($fixtureIdentifier);

        $definitions = $this->fileParser->parse($rawData);

        if (empty($definitions[$fixtureIdentifier->name()])) {
            throw new \RuntimeException("Could not find fixture \"{$fixtureIdentifier->toString()}\"");
        }

        return $definitions[$fixtureIdentifier->name()];
    }
}
