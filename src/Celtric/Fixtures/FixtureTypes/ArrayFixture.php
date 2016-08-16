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
        return array_map(function (FixtureDefinition $d) { return $d->instantiate(); }, $this->data);
    }
}
