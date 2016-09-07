<?php

namespace Celtric\Fixtures\DefinitionLocators;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureIdentifier;

final class LocatorCacher implements DefinitionLocator
{
    /** @var DefinitionLocator */
    private $wrapperLocator;

    /** @var array */
    private $cache = [];

    /**
     * @param DefinitionLocator $wrapperLocator
     */
    public function __construct(DefinitionLocator $wrapperLocator)
    {
        $this->wrapperLocator = $wrapperLocator;
    }

    /**
     * @inheritDoc
     */
    public function fixtureDefinition(FixtureIdentifier $fixtureIdentifier)
    {
        if (empty($this->cache['fixture'][$fixtureIdentifier->toString()])) {
            $this->cache['fixture'][$fixtureIdentifier->toString()] = $this->wrapperLocator->fixtureDefinition($fixtureIdentifier);
        }

        return $this->cache['fixture'][$fixtureIdentifier->toString()];
    }

    /**
     * @inheritDoc
     */
    public function namespaceDefinitions($namespace)
    {
        if (empty($this->cache['namespace'][$namespace])) {
            $this->cache['namespace'][$namespace] = $this->wrapperLocator->namespaceDefinitions($namespace);
        }

        return $this->cache['namespace'][$namespace];
    }
}
