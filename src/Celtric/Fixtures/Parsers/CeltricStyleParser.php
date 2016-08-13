<?php

namespace Celtric\Fixtures\Parsers;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureDefinitionFactory;
use Celtric\Fixtures\RawDataParser;
use Celtric\Fixtures\FixtureDefinition;

final class CeltricStyleParser implements RawDataParser
{
    const DEFAULT_TYPE = "array";

    /** @var FixtureDefinitionFactory */
    private $definitionFactory;

    public function __construct()
    {
        $this->definitionFactory = new FixtureDefinitionFactory();
    }

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
                $parsedData[$key] = $this->definitionFactory->reference(substr($value, 1));
                continue;
            }

            $isMethod = method_exists($defaultType, $key);

            if ($isMethod) {
                $parsedData[$key] = $this->definitionFactory->methodCall(
                        $this->parseData(is_array($value) ? $value : [$value], "array"));
                continue;
            }

            if (preg_match("/^(.*)<(.*)>$/", $key, $matches)) {
                list(, $key, $type) = $matches;
            } elseif (is_array($value)) {
                $type = $defaultType;
            } else {
                $type = $this->resolveType($value);
            }

            $parsedData[$key] = $this->definitionFactory->generic($type, $this->parseData($value, $type));
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
