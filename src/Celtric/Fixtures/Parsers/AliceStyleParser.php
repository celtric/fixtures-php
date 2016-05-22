<?php

namespace Celtric\Fixtures\Parsers;

use Celtric\Fixtures\FixtureDefinition;
use Celtric\Fixtures\RawDataParser;

final class AliceStyleParser implements RawDataParser
{
    /**
     * @inheritDoc
     */
    public function parse(array $rawData)
    {
        $definitions = [];

        foreach ($rawData as $type => $typeRawDefinitions) {
            foreach ($typeRawDefinitions as $name => $values) {
                $definitions[$name] = new FixtureDefinition($type, $this->parseValues($values));
            }
        }

        return $definitions;
    }

    /**
     * @param array $rawValues
     * @return FixtureDefinition[]
     */
    private function parseValues(array $rawValues)
    {
        $parsedValues = [];

        foreach ($rawValues as $key => $value) {
            $isReference = is_string($value) && $value[0] === "@";

            if ($isReference) {
                $parsedValues[$key] = new FixtureDefinition("reference", substr($value, 1));
                continue;
            }

            $parsedValues[$key] = new FixtureDefinition($this->resolveType($value), $value);
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
