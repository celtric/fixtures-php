<?php

namespace Celtric\Fixtures\FixtureTypes;

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
    public function instantiate()
    {
        $instantiatedData = [];

        foreach ($this->data as $key => $value) {
            $instantiatedData[$key] = $value->instantiate();
        }

        return $instantiatedData;
    }
}
