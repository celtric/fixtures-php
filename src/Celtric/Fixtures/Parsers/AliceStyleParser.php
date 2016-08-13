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
                $isRange = preg_match("/(.*)\\{(\\d+)(\\.{2,})(\\d+)\\}/i", $name, $matches);

                if ($isRange) {
                    $baseName = $matches[1];
                    $from = $matches[2];
                    $to = $matches[4];

                    foreach (range($from, $to) as $i) {
                        $definitions[$baseName . $i] = $this->definitionFactory->generic($type, $this->parseValues(
                                $type,
                                $values,
                                $definitionLocator));
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

                        $definitions[$baseName . $i] = $this->definitionFactory->generic($type, $this->parseValues(
                                $type,
                                $itemValues,
                                $definitionLocator));
                    }

                    continue;
                }

                $definitions[$name] = $this->definitionFactory->generic($type, $this->parseValues($type, $values, $definitionLocator));
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

            $parsedValues[$key] = $this->definitionFactory->generic($this->resolveType($value), $value);
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
