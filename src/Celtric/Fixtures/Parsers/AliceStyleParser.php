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

        foreach ($rawData as $type => $typeRawDefinitions) {
            foreach ($typeRawDefinitions as $name => $values) {
                $definitions[$name] = $this->toDefinition($type, $this->parseValues($type, $values, $definitionLocator));
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
    private function parseValues($type, array $rawValues, DefinitionLocator $definitionLocator)
    {
        $parsedValues = [];

        foreach ($rawValues as $key => $value) {
            $isReference = is_string($value) && $value[0] === "@";

            if ($isReference) {
                $parsedValues[$key] = $this->definitionFactory->reference(substr($value, 1), $definitionLocator);
                continue;
            }

            $isMethod = method_exists($type, $key);

            if ($isMethod) {
                $parsedValues[$key] = $this->definitionFactory->methodCall($this->parseValues(
                        $type,
                        $value,
                        $definitionLocator));
                continue;
            }

            $parsedValues[$key] = $this->toDefinition($type, $value);
        }

        return $parsedValues;
    }

    /**
     * @param string $type
     * @param mixed $parsedValue
     * @return FixtureDefinition
     */
    private function toDefinition($type, $parsedValue)
    {
        switch (true) {
            case is_null($parsedValue):
                return $this->definitionFactory->null();
            case is_scalar($parsedValue):
                return $this->definitionFactory->scalar($parsedValue);
            case $type === "array":
                return $this->definitionFactory->arr($parsedValue);
            default:
                return $this->definitionFactory->object($type, $parsedValue);
        }
    }
}
