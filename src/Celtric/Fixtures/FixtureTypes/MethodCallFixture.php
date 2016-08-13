<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\FixtureDefinition;

final class MethodCallFixture implements FixtureDefinition
{
    /** @var FixtureDefinition[] */
    private $arguments;

    /**
     * @param FixtureDefinition[] $arguments
     */
    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @inheritDoc
     */
    public function instantiate()
    {
        $instantiatedData = [];

        foreach ($this->arguments as $key => $value) {
            $instantiatedData[$key] = $value->instantiate();
        }

        return $instantiatedData;
    }
}
