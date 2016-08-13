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
        if (!empty($rawData["root_type"])) {
            if (!is_string($rawData["root_type"])) {
                throw new \RuntimeException("Root type must be defined as a string");
            }
            $rootType = $rawData["root_type"];
            unset($rawData["root_type"]);
        } else {
            $rootType = self::DEFAULT_TYPE;
        }

        return $this->parseData($rawData, $rootType, $definitionLocator);
    }

    /**
     * @param mixed $rawData
     * @param string $defaultType
     * @param DefinitionLocator $definitionLocator
     * @return FixtureDefinition[]
     */
    private function parseData($rawData, $defaultType, DefinitionLocator $definitionLocator)
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
                $parsedData[$key] = $this->definitionFactory->reference(substr($value, 1), $definitionLocator);
                continue;
            }

            $isMethod = method_exists($defaultType, $key);

            if ($isMethod) {
                $parsedData[$key] = $this->definitionFactory->methodCall(
                        $this->parseData(is_array($value) ? $value : [$value], "array", $definitionLocator));
                continue;
            }

            if (preg_match("/^(.*)<(.*)>$/", $key, $matches)) {
                list(, $key, $type) = $matches;
            } elseif (is_array($value)) {
                $type = $defaultType;
            } else {
                $type = $this->resolveType($value);
            }

            $parsedValue = $this->parseData($value, $type, $definitionLocator);

            switch (true) {
                case is_null($parsedValue):
                    $parsedData[$key] = $this->definitionFactory->null();
                    break;
                case is_scalar($parsedValue):
                    $parsedData[$key] = $this->definitionFactory->scalar($parsedValue);
                    break;
                case $type === "array":
                    $parsedData[$key] = $this->definitionFactory->arr($parsedValue);
                    break;
                default:
                    $parsedData[$key] = $this->definitionFactory->object($type, $parsedValue);
                    break;
            }
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
