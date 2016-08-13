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
        $rootType = self::DEFAULT_TYPE;

        if (!empty($rawData["root_type"])) {
            if (!is_string($rawData["root_type"])) {
                throw new \RuntimeException("Root type must be defined as a string");
            }
            $rootType = $rawData["root_type"];
            unset($rawData["root_type"]);
        }

        return $this->parseRecursively($rawData, $rootType, $definitionLocator);
    }

    /**
     * @param mixed $rawData
     * @param string $defaultType
     * @param DefinitionLocator $definitionLocator
     * @return FixtureDefinition[]
     */
    private function parseRecursively($rawData, $defaultType, DefinitionLocator $definitionLocator)
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
            $isMethod = method_exists($defaultType, $key);

            switch (true) {
                case $isReference:
                    $definition = $this->definitionFactory->reference(substr($value, 1), $definitionLocator);
                    break;
                case $isMethod:
                    $definition = $this->definitionFactory->methodCall(
                            $this->parseRecursively(is_array($value) ? $value : [$value], "array", $definitionLocator));
                    break;
                default:
                    $type = null;

                    if (preg_match("/^(.*)<(.*)>$/", $key, $matches)) {
                        list(, $key, $type) = $matches;
                    } elseif (is_array($value)) {
                        $type = $defaultType;
                    }

                    $definition = $this->toDefinition($type, $this->parseRecursively($value, $type, $definitionLocator));
            }

            $parsedData[$key] = $definition;
        }

        return $parsedData;
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
