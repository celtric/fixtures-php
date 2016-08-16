<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\FixtureDefinition;

final class MethodCallArgumentsFixture implements FixtureDefinition
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
        return array_map(function (FixtureDefinition $d) { return $d->instantiate(); }, $this->arguments);
    }
}
