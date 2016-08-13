<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureDefinition;

final class ArrayFixture implements FixtureDefinition
{
    /** @var FixtureDefinition[] */
    private $data;

    /**
     * @param FixtureDefinition[] $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function instantiate(DefinitionLocator $definitionLocator)
    {
        $instantiatedData = [];

        foreach ($this->data as $key => $value) {
            $instantiatedData[$key] = $value->instantiate($definitionLocator);
        }

        return $instantiatedData;
    }
}
