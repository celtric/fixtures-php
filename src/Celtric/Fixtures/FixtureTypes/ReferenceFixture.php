<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureDefinition;
use Celtric\Fixtures\FixtureIdentifier;

final class ReferenceFixture implements FixtureDefinition
{
    /** @var string */
    private $reference;

    /** @var DefinitionLocator */
    private $definitionLocator;

    /**
     * @param string $reference
     * @param DefinitionLocator $definitionLocator
     */
    public function __construct($reference, DefinitionLocator $definitionLocator)
    {
        $this->reference = $reference;
        $this->definitionLocator = $definitionLocator;
    }

    /**
     * @inheritDoc
     */
    public function instantiate()
    {
        return $this->definitionLocator->locate(new FixtureIdentifier($this->reference))->instantiate();
    }
}
