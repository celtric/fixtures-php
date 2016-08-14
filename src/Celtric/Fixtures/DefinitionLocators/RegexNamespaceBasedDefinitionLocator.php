<?php

namespace Celtric\Fixtures\DefinitionLocators;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureIdentifier;
use Celtric\Fixtures\RawDataLocator;
use Celtric\Fixtures\RawDataParser;

final class RegexNamespaceBasedDefinitionLocator implements DefinitionLocator
{
    /** @var RawDataLocator */
    private $rawDataLocator;

    /** @var RawDataParser[] */
    private $parsers;

    /**
     * @param RawDataLocator $rawDataLocator
     * @param RawDataParser[] $parsers
     */
    public function __construct(RawDataLocator $rawDataLocator, array $parsers)
    {
        $this->rawDataLocator = $rawDataLocator;
        $this->parsers = $parsers;
    }

    /**
     * @inheritDoc
     */
    public function fixtureDefinition(FixtureIdentifier $fixtureIdentifier)
    {
        $definitions = $this->namespaceDefinitions($fixtureIdentifier->getNamespace());

        if (empty($definitions[$fixtureIdentifier->name()])) {
            throw new \RuntimeException("Could not find fixture \"{$fixtureIdentifier->toString()}\"");
        }

        return $definitions[$fixtureIdentifier->name()];
    }

    /**
     * @inheritDoc
     */
    public function namespaceDefinitions($namespace)
    {
        $rawData = $this->rawDataLocator->retrieveRawData($namespace);

        foreach ($this->parsers as $namespaceMatcher => $parser) {
            if (preg_match($namespaceMatcher, $namespace)) {
                return $parser->parse($rawData, $this);
            }
        }

        throw new \RuntimeException("No parser found for namespace \"{$namespace}\"");
    }
}
