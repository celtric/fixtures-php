<?php

namespace Celtric\Fixtures\Parsers;

use Celtric\Fixtures\RawDataParser;
use Celtric\Fixtures\FixtureDefinition;

final class CeltricStyleParser implements RawDataParser
{
    const DEFAULT_TYPE = "array";

    /**
     * @inheritDoc
     */
    public function parse(array $rawData)
    {
        if (!empty($rawData["root_type"])) {
            if (!is_string($rawData["root_type"])) {
                throw new \RuntimeException("Root type must be defined as a string");
            }
            $rootType = $rawData["root_type"];
            unset($rawData["root_type"]);
        } else {
            $rootType = self::DEFAULT_TYPE;
        }

        return $this->parseData($rawData, $rootType);
    }

    /**
     * @param mixed $rawData
     * @param string $defaultType
     * @return FixtureDefinition[]
     */
    private function parseData($rawData, $defaultType)
    {
        if (!is_array($rawData)) {
            if ($defaultType === "array") {
                return (array) $rawData;
            } else {
                return $rawData;
            }
        }

        $parsedData = [];

        foreach ($rawData as $key => $value) {
            $isReference = is_string($value) && $value[0] === "@";

            if ($isReference) {
                $parsedData[$key] = new FixtureDefinition("reference", substr($value, 1));
                continue;
            }

            if (preg_match("/^(.*)<(.*)>$/", $key, $matches)) {
                list(, $key, $type) = $matches;
            } elseif (is_array($value)) {
                $type = $defaultType;
            } else {
                $type = $this->resolveType($value);
            }

            $parsedData[$key] = new FixtureDefinition($type, $this->parseData($value, $type));
        }

        return $parsedData;
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
