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
                $isRange = preg_match("/(.*)\\{(\\d+)(\\.{2,})(\\d+)\\}/i", $name, $matches);

                if ($isRange) {
                    $baseName = $matches[1];
                    $from = $matches[2];
                    $to = $matches[4];

                    foreach (range($from, $to) as $i) {
                        $definitions[$baseName . $i] = FixtureDefinition::generic($type, $this->parseValues($type, $values));
                    }

                    continue;
                }

                $isCustomList = preg_match("/(.*)\\{([^,]+(\\s*,\\s*[^,]+)*)\\}/", $name, $matches);

                if ($isCustomList) {
                    $baseName = $matches[1];
                    $listItems = array_map("trim", explode(",", $matches[2]));

                    foreach ($listItems as $i) {
                        $itemValues = array_map(function($value) use ($i) {
                            return str_replace("<current()>", $i, $value);
                        }, $values);

                        $definitions[$baseName . $i] = FixtureDefinition::generic($type, $this->parseValues($type, $itemValues));
                    }

                    continue;
                }

                $definitions[$name] = FixtureDefinition::generic($type, $this->parseValues($type, $values));
            }
        }

        return $definitions;
    }

    /**
     * @param string $type
     * @param array $rawValues
     * @return FixtureDefinition[]
     */
    private function parseValues($type, array $rawValues)
    {
        $parsedValues = [];

        foreach ($rawValues as $key => $value) {
            $isReference = is_string($value) && $value[0] === "@";

            if ($isReference) {
                $parsedValues[$key] = FixtureDefinition::reference(substr($value, 1));
                continue;
            }

            $isMethod = method_exists($type, $key);

            if ($isMethod) {
                $parsedValues[$key] = FixtureDefinition::methodCall($this->parseValues($type, $value));
                continue;
            }

            $parsedValues[$key] = FixtureDefinition::generic($this->resolveType($value), $value);
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
