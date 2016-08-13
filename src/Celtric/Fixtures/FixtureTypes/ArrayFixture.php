<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureDefinition;

final class ArrayFixture extends FixtureDefinition
{
    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct("array", $data);
    }

    /**
     * @inheritDoc
     */
    public function instantiate(DefinitionLocator $definitionLocator)
    {
        $instantiatedData = [];

        foreach ($this->data() as $key => $value) {
            $instantiatedData[$key] = $value->instantiate($definitionLocator);
        }

        return $instantiatedData;
    }
}
