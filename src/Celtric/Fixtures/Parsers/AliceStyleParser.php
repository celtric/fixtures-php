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
                $parsedValue = $this->parseValues($type, $values, $definitionLocator);

                switch (true) {
                    case is_null($parsedValue):
                        $definitions[$name] = $this->definitionFactory->null();
                        break;
                    case is_scalar($parsedValue):
                        $definitions[$name] = $this->definitionFactory->scalar($parsedValue);
                        break;
                    case $type === "array":
                        $definitions[$name] = $this->definitionFactory->arr($parsedValue);
                        break;
                    default:
                        $definitions[$name] = $this->definitionFactory->object($type, $parsedValue);
                        break;
                }
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

            switch (true) {
                case is_null($value):
                    $parsedValues[$key] = $this->definitionFactory->null();
                    break;
                case is_scalar($value):
                    $parsedValues[$key] = $this->definitionFactory->scalar($value);
                    break;
                case $this->resolveType($value) === "array":
                    $parsedValues[$key] = $this->definitionFactory->arr($value);
                    break;
                default:
                    $parsedValues[$key] = $this->definitionFactory->object($type, $value);
                    break;
            }
        }

        return $parsedValues;
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function resolveType($value)
    {
        $nativeType = strtolower(gettype($value));

        if ($nativeType === "double") {
            return "float";
        }

        return $nativeType;
    }
}
