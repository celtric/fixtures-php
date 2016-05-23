<?php

namespace Celtric\Fixtures\DefinitionLocators;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureIdentifier;
use Celtric\Fixtures\RawDataLocator;
use Celtric\Fixtures\RawDataParser;

final class RegexNamespaceBasedDefinitionLocator implements DefinitionLocator
{
    /** @var RawDataParser[] */
    private $parsers;

    /** @var RawDataLocator */
    private $rawDataLocator;

    /**
     * @param RawDataLocator $rawDataLocator
     * @param RawDataParser[] $parsers
     */
    public function __construct(RawDataLocator $rawDataLocator, array $parsers)
    {
        $this->parsers = $parsers;
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

        foreach ($this->parsers as $namespaceMatcher => $parser) {
            if (preg_match($namespaceMatcher, $namespace)) {
                return $parser->parse($rawData);
            }
        }

        throw new \RuntimeException("No parser found for namespace \"{$namespace}\"");
    }
}
