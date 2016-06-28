<?php

namespace Celtric\Fixtures\Parsers;

use Celtric\Fixtures\RawDataParser;
use Celtric\Fixtures\FixtureDefinition;
use phpDocumentor\Reflection\DocBlock;

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

        return $this->parseData($rawData, $rootType, true);
    }

    /**
     * @param mixed $rawData
     * @param string $defaultType
     * @param bool $isRoot
     * @return FixtureDefinition[]
     */
    private function parseData($rawData, $defaultType, $isRoot)
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

            $isMethod = method_exists($defaultType, $key);

            if ($isMethod) {
                $parsedData[$key] = new FixtureDefinition(
                        "method_call",
                        $this->parseData(is_array($value) ? $value : [$value], "array", false));
                continue;
            }

            if (preg_match("/^(.*)<(.*)>$/", $key, $matches)) {
                list(, $key, $type) = $matches;
            } elseif (is_array($value)) {
                if ($isRoot) {
                    $type = $defaultType;
                } else {
                    $type = $this->resolveCustomType($defaultType, $key);
                }
            } else {
                $type = $this->resolveNativeType($value);
            }

            $parsedData[$key] = new FixtureDefinition($type, $this->parseData($value, $type, false));
        }

        return $parsedData;
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function resolveNativeType($value)
    {
        $nativeType = strtolower(gettype($value));

        if ($nativeType === "double") {
            return "float";
        }

        return $nativeType;
    }

    /**
     * @param string $parentType
     * @param string $propertyName
     * @return string
     */
    private function resolveCustomType($parentType, $propertyName)
    {
        if ($parentType === "array") {
            return "array";
        }

        $comment = (new \ReflectionProperty($parentType, $propertyName))->getDocComment();

        if (empty($comment)) {
            return "array";
        }

        $docBlock = new DocBlock($comment);
        $tags = $docBlock->getTagsByName("var");

        if (empty($tags)) {
            return "array";
        }

        $namespace = (new \ReflectionClass($parentType))->getNamespaceName();

        return "{$namespace}\\{$tags[0]->getContent()}";
    }
}
