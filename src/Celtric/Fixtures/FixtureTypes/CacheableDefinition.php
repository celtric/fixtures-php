<?php

namespace Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\FixtureDefinition;

final class CacheableDefinition implements FixtureDefinition
{
    /** @var FixtureDefinition */
    private $definition;

    /** @var mixed */
    private $cache;

    /** @var bool */
    private $cloneObjects;

    /**
     * @param FixtureDefinition $definition
     * @param bool $cloneObjects
     */
    public function __construct(FixtureDefinition $definition, $cloneObjects)
    {
        $this->definition = $definition;
        $this->cloneObjects = $cloneObjects;
    }

    /**
     * @inheritDoc
     */
    public function instantiate()
    {
        if (empty($this->cache)) {
            $this->cache = $this->definition->instantiate();
        }

        return $this->cloneObjects && is_object($this->cache) ? clone $this->cache : $this->cache;
    }
}
