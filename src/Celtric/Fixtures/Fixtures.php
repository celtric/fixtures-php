<?php

namespace Celtric\Fixtures;

use Symfony\Component\Yaml\Parser;

final class Fixtures
{
    const DEFAULT_TYPE = "array";

    /** @var string */
    private $rootPath;

    /**
     * @param string $rootPath
     */
    public function __construct($rootPath)
    {
        $this->rootPath = $rootPath;
    }

    /**
     * @param string $fullFixtureName
     * @return mixed
     */
    public function fixture($fullFixtureName)
    {
        $fixtureIdentifier = FixtureIdentifier::fromFullFixtureName($fullFixtureName);

        $yamlDefinitions = file_get_contents($fixtureIdentifier->toFilePath($this->rootPath));
        
        $definitions = (new Parser())->parse($yamlDefinitions);

        if (!empty($definitions["root_type"])) {
            if (!is_string($definitions["root_type"])) {
                throw new \RuntimeException("Root type must be defined as a string");
            }
            $rootType = $definitions["root_type"];
            unset($definitions["root_type"]);
        } else {
            $rootType = self::DEFAULT_TYPE;
        }

        $fixtureDefinition = null;
        $fixtureType = null;

        if (array_key_exists($fixtureIdentifier->fixtureName(), $definitions)) {
            $fixtureDefinition = $definitions[$fixtureIdentifier->fixtureName()];
            $fixtureType = $rootType;
        } else {
            foreach ($definitions as $definitionName => $definitionData) {
                if (preg_match("/^{$fixtureIdentifier->fixtureName()}<(.*)>$/", $definitionName, $matches)) {
                    $fixtureDefinition = $definitions[$matches[0]];
                    $fixtureType = $matches[1];
                    break;
                }
            }
        }
        
        if (empty($fixtureType)) {
            throw new \RuntimeException("Could not find fixture \"{$fullFixtureName}\"");
        }

        if ($fixtureType !== "array" && !class_exists($fixtureType)) {
            throw new \RuntimeException("Could not find type \"{$fixtureType}\"");
        }
        
        if ($fixtureType === "array" && empty($fixtureDefinition)) {
            $fixtureDefinition = [];
        }

        return $this->convertDefinition($fixtureDefinition, $fixtureType);
    }

    /**
     * @param mixed $fixtureDefinition
     * @param string $type
     * @return array
     */
    private function convertDefinition($fixtureDefinition, $type)
    {
        if (!is_array($fixtureDefinition)) {
            $isReference = substr($fixtureDefinition, 0, 1) === "@";

            if ($isReference) {
                $fixtureName = substr($fixtureDefinition, 1);
                return $this->fixture($fixtureName);
            }

            return $fixtureDefinition;
        }

        $values = [];

        foreach ($fixtureDefinition as $key => $value) {
            if (preg_match("/^(.*)<(.*)>$/", $key, $matches)) {
                $values[$matches[1]] = $this->convertDefinition($value, $matches[2]);
            } else {
                $values[$key] = $this->convertDefinition($value, "array");
            }
        }

        return $this->castTo($type, $values);
    }

    /**
     * @param string $type
     * @param array $values
     * @return mixed
     */
    private function castTo($type, $values)
    {
        if ($type === "array") {
            return $values;
        }

        $serializedValues = serialize((object) $values);

        return unserialize(
                "O:"
                . strlen($type)
                . ":\""
                . $type
                . "\":"
                . substr($serializedValues, $serializedValues[2] + 7));
    }
}
