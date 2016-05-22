<?php

namespace Celtric\Fixtures;

final class DefinitionLocator
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
