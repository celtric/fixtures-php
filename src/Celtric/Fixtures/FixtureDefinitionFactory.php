<?php

namespace Celtric\Fixtures;

use Celtric\Fixtures\FixtureTypes\ArrayFixture;
use Celtric\Fixtures\FixtureTypes\MethodCallArgumentsFixture;
use Celtric\Fixtures\FixtureTypes\NullFixture;
use Celtric\Fixtures\FixtureTypes\ScalarFixture;
use Celtric\Fixtures\FixtureTypes\ObjectFixture;
use Celtric\Fixtures\FixtureTypes\ReferenceFixture;

class FixtureDefinitionFactory
{
    /**
     * @return FixtureDefinition
     */
    public function null()
    {
        return new NullFixture();
    }

    /**
     * @param mixed $value
     * @return FixtureDefinition
     */
    public function scalar($value)
    {
        return new ScalarFixture($value);
    }

    /**
     * @param array $data
     * @return FixtureDefinition
     */
    public function arr(array $data)
    {
        return new ArrayFixture($data);
    }

    /**
     * @param string $className
     * @param array $properties
     * @return FixtureDefinition
     */
    public function object($className, array $properties)
    {
        return new ObjectFixture($className, $properties);
    }

    /**
     * @param array $args
     * @return FixtureDefinition
     */
    public function methodCallArguments(array $args)
    {
        return new MethodCallArgumentsFixture($args);
    }

    /**
     * @param string $reference
     * @param DefinitionLocator $definitionLocator
     * @return FixtureDefinition
     */
    public function reference($reference, DefinitionLocator $definitionLocator)
    {
        return new ReferenceFixture(new FixtureIdentifier($reference), $definitionLocator);
    }
}
