<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\FixtureDefinition;

final class CacheableDefinition implements FixtureDefinition
{
    /** @var FixtureDefinition */
    private $definition;

    /** @var mixed */
    private $cache;

    /**
     * @param FixtureDefinition $definition
     */
    public function __construct(FixtureDefinition $definition)
    {
        $this->definition = $definition;
    }

    /**
     * @inheritDoc
     */
    public function instantiate()
    {
        if (empty($this->cache)) {
            $this->cache = $this->definition->instantiate();
        }

        return $this->cache;
    }
}
