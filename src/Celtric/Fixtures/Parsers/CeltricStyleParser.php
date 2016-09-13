<?php

namespace Celtric\Fixtures\Parsers;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureDefinitionFactory;
use Celtric\Fixtures\RawDataParser;
use Celtric\Fixtures\FixtureDefinition;
use phpDocumentor\Reflection\DocBlock;

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
        return is_array($rawData)
                ? $this->parseArrayRawData($rawData, $defaultType, $definitionLocator)
                : $this->parseNonArrayRawData($rawData, $defaultType);
    }

    /**
     * @param mixed $rawData
     * @param $defaultType
     * @return mixed
     */
    private function parseNonArrayRawData($rawData, $defaultType)
    {
        if (class_exists($defaultType)) {
            return ["__construct" => $rawData];
        } elseif ($defaultType === "array") {
            return (array) $rawData;
        } else {
            return $rawData;
        }
    }

    /**
     * @param array $rawData
     * @param string $defaultType
     * @param DefinitionLocator $definitionLocator
     * @return array
     */
    private function parseArrayRawData(array $rawData, $defaultType, DefinitionLocator $definitionLocator)
    {
        $parsedData = [];

        foreach ($rawData as $key => $value) {
            $parsedData[$this->fixtureNameWithoutType($key)] = $this->toDefinition(
                    $key,
                    $value,
                    $defaultType,
                    $definitionLocator);
        }

        return $parsedData;
    }

    /**
     * @param string $key
     * @return string
     */
    private function fixtureNameWithoutType($key)
    {
        return preg_match("/^(.*)<(.*)>$/", $key, $matches) ? $matches[1] : $key;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param $defaultType
     * @param DefinitionLocator $definitionLocator
     * @return FixtureDefinition
     */
    private function toDefinition($key, $value, $defaultType, DefinitionLocator $definitionLocator)
    {
        $isReference = is_string($value) && strpos($value, "@") === 0;
        $isMethod = method_exists($defaultType, $key);
        $type = null;

        if (preg_match("/^(.*)<(.*)>$/", $key, $matches)) {
            $type = $matches[2];
        } elseif ($this->classPropertyTypeIsAvailable($defaultType, $key)) {
            $type = $this->extractClassPropertyType($defaultType, $key);
        } elseif (is_array($value)) {
            $type = $defaultType;
        }

        $parsedValue = $this->parseRecursively($value, $type, $definitionLocator);

        switch (true) {
            case $isReference:
                return $this->definitionFactory->reference(substr($value, 1), $definitionLocator);
            case $isMethod:
                return $this->definitionFactory->arr(
                        $this->parseRecursively(is_array($value) ? $value : [$value], "array", $definitionLocator));
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

    /**
     * @param string $className
     * @param string $propertyName
     * @return bool
     */
    private function classPropertyTypeIsAvailable($className, $propertyName)
    {
        return $this->extractClassPropertyType($className, $propertyName) !== null;
    }

    /**
     * @param string $className
     * @param string $propertyName
     * @return string|null
     */
    private function extractClassPropertyType($className, $propertyName)
    {
        if ($className === "array" || !property_exists($className, $propertyName)) {
            return null;
        }

        $comment = (new \ReflectionProperty($className, $propertyName))->getDocComment();

        if (empty($comment)) {
            return null;
        }

        $docBlock = new DocBlock($comment);
        $tags = $docBlock->getTagsByName("var");

        if (empty($tags)) {
            return null;
        }

        $docBlockType = $tags[0]->getContent();

        if ($this->isNativeType($docBlockType)) {
            return $docBlockType;
        }

        if (substr($docBlockType, 0, 1) === "\\") {
            return $docBlockType;
        }

        $namespace = (new \ReflectionClass($className))->getNamespaceName();

        return "{$namespace}\\{$docBlockType}";
    }

    /**
     * @param string $type
     * @return bool
     */
    private function isNativeType($type)
    {
        return in_array($type, ['int', 'integer', 'bool', 'boolean', 'string', 'null'], true);
    }
}
