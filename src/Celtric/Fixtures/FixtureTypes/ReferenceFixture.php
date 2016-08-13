<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureDefinition;
use Celtric\Fixtures\FixtureIdentifier;

final class ReferenceFixture implements FixtureDefinition
{
    /** @var string */
    private $reference;

    /**
     * @param string $reference
     */
    public function __construct($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @inheritDoc
     */
    public function instantiate(DefinitionLocator $definitionLocator)
    {
        return $definitionLocator->locate(new FixtureIdentifier($this->reference))->instantiate($definitionLocator);
    }
}
