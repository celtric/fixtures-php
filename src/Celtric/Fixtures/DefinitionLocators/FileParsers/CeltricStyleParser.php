<?php

namespace Celtric\Fixtures\DefinitionLocators\FileParsers;

use Celtric\Fixtures\DefinitionLocators\FileParser;
use Celtric\Fixtures\FixtureDefinition;
use Symfony\Component\Yaml\Parser;

final class CeltricStyleParser implements FileParser
{
    const DEFAULT_TYPE = "array";

    /**
     * @inheritDoc
     */
    public function parse($fileContent)
    {
        $rawData = (new Parser())->parse($fileContent);

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
     * @return array
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
        } else {
            return $nativeType;
        }
    }
}
