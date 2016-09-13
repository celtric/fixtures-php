<?php

namespace Celtric\Fixtures;

use Celtric\Fixtures\FixtureTypes\CacheableDefinition;

class CachedDefinitionFactory extends FixtureDefinitionFactory
{
    /** @var \ArrayObject */
    private $cache;

    /** @var FixtureDefinitionFactory */
    private $wrappedFactory;

    /** @var bool */
    private $cloneObjects;

    /**
     * @param \ArrayObject $cache
     * @param FixtureDefinitionFactory $wrappedFactory
     * @param bool $cloneObjects
     */
    public function __construct(\ArrayObject $cache, FixtureDefinitionFactory $wrappedFactory, $cloneObjects)
    {
        $this->cache = $cache;
        $this->wrappedFactory = $wrappedFactory;
        $this->cloneObjects = $cloneObjects;
    }

    /**
     * @inheritDoc
     */
    public function null()
    {
        return $this->wrappedFactory->null();
    }

    /**
     * @inheritDoc
     */
    public function scalar($value)
    {
        return $this->wrappedFactory->scalar($value);
    }

    /**
     * @inheritDoc
     */
    public function arr(array $data)
    {
        return $this->cache($this->hash($data), $this->wrappedFactory->arr($data));
    }
    /**
     * @inheritDoc
     */
    public function object($className, array $properties)
    {
        return $this->cache($this->hash([$className, $properties]), $this->wrappedFactory->object($className, $properties));
    }

    /**
     * @inheritDoc
     */
    public function reference($reference, DefinitionLocator $definitionLocator)
    {
        return $this->cache($reference, $this->wrappedFactory->reference($reference, $definitionLocator));
    }

    /**
     * @param string $hash
     * @param FixtureDefinition $definition
     * @return FixtureDefinition
     */
    private function cache($hash, FixtureDefinition $definition)
    {
        if (empty($this->cache[$hash])) {
            $this->cache[$hash] = new CacheableDefinition($definition, $this->cloneObjects);
        }

        return $this->cache[$hash];
    }

    /**
     * @param mixed $data
     * @return string
     */
    private function hash($data)
    {
        return md5(serialize($data));
    }
}
