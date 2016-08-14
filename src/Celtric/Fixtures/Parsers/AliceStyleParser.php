<?php

namespace Celtric\Fixtures\Parsers;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureDefinition;
use Celtric\Fixtures\FixtureDefinitionFactory;
use Celtric\Fixtures\RawDataParser;

final class AliceStyleParser implements RawDataParser
{
    /** @var FixtureDefinitionFactory */
    private $definitionFactory;

    /**
     * @param FixtureDefinitionFactory $definitionFactory
     */
    public function __construct(FixtureDefinitionFactory $definitionFactory)
    {
        $this->definitionFactory = $definitionFactory;
    }

    /**
     * @inheritDoc
     */
    public function parse(array $rawData, DefinitionLocator $definitionLocator)
    {
        $definitions = [];

        foreach ($rawData as $className => $rawDefinitionsOfThisClass) {
            /** @var array $rawDefinitionsOfThisClass*/
            foreach ($rawDefinitionsOfThisClass as $fixtureName => $fixtureValues) {
                $definitions[$fixtureName] = $this->definitionFactory->object(
                        $className,
                        $this->parseSingleFixtureValues($className, $fixtureValues, $definitionLocator));
            }
        }

        return $definitions;
    }

    /**
     * @param string $type
     * @param array $rawValues
     * @param DefinitionLocator $definitionLocator
     * @return FixtureDefinition[]
     */
    private function parseSingleFixtureValues($type, array $rawValues, DefinitionLocator $definitionLocator)
    {
        $parsedValues = [];

        foreach ($rawValues as $key => $value) {
            $parsedValues[$key] = $this->toDefinition($key, $type, $value, $definitionLocator);
        }

        return $parsedValues;
    }

    /**
     * @param string $key
     * @param string $type
     * @param mixed $value
     * @param DefinitionLocator $definitionLocator
     * @return FixtureDefinition
     */
    private function toDefinition($key, $type, $value, DefinitionLocator $definitionLocator)
    {
        $isReference = is_string($value) && $value[0] === "@";
        $isMethod = method_exists($type, $key);

        switch (true) {
            case $isReference:
                return $this->definitionFactory->reference(substr($value, 1), $definitionLocator);
            case $isMethod:
                return $this->definitionFactory->methodCall($this->parseSingleFixtureValues($type, $value, $definitionLocator));
            case is_null($value):
                return $this->definitionFactory->null();
            case is_scalar($value):
                return $this->definitionFactory->scalar($value);
            case $type === "array":
                return $this->definitionFactory->arr($value);
            default:
                return $this->definitionFactory->object($type, $value);
        }
    }
}
