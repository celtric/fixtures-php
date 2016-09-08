<?php

namespace Celtric\Fixtures;

use Celtric\Fixtures\FixtureTypes\CacheableDefinition;

class CachedDefinitionFactory extends FixtureDefinitionFactory
{
    /** @var FixtureDefinition[] */
    private static $cache = [];

    /**
     * @inheritDoc
     */
    public function arr(array $data)
    {
        return $this->cache($this->serialize($data), parent::arr($data));
    }
    /**
     * @inheritDoc
     */
    public function object($className, array $properties)
    {
        return $this->cache($this->serialize([$className, $properties]), parent::object($className, $properties));
    }

    /**
     * @inheritDoc
     */
    public function reference($reference, DefinitionLocator $definitionLocator)
    {
        return $this->cache($reference, parent::reference($reference, $definitionLocator));
    }

    /**
     * @param string $hash
     * @param FixtureDefinition $definition
     * @return FixtureDefinition
     */
    private function cache($hash, FixtureDefinition $definition)
    {
        if (empty(static::$cache[$hash])) {
            static::$cache[$hash] = new CacheableDefinition($definition);
        }

        return static::$cache[$hash];
    }

    /**
     * @param mixed $data
     * @return string
     */
    private function serialize($data)
    {
        return md5(serialize($data));
    }
}
