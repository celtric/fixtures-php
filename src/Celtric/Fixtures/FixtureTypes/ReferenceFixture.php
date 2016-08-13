<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureDefinition;
use Celtric\Fixtures\FixtureIdentifier;

final class ReferenceFixture implements FixtureDefinition
{
    /** @var FixtureIdentifier */
    private $reference;

    /** @var DefinitionLocator */
    private $definitionLocator;

    /**
     * @param FixtureIdentifier $reference
     * @param DefinitionLocator $definitionLocator
     */
    public function __construct(FixtureIdentifier $reference, DefinitionLocator $definitionLocator)
    {
        $this->reference = $reference;
        $this->definitionLocator = $definitionLocator;
    }

    /**
     * @inheritDoc
     */
    public function instantiate()
    {
        return $this->definitionLocator->locate($this->reference)->instantiate();
    }
}
