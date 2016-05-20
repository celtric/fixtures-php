<?php

namespace Celtric\Fixtures;

use Symfony\Component\Yaml\Parser;

final class Fixtures
{
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
        list($namespace, $fixtureName) = explode(".", $fullFixtureName);

        $yamlDefinitions = file_get_contents(
                $this->rootPath . str_replace(".", DIRECTORY_SEPARATOR, $namespace) . ".yml");
        
        $definitions = (new Parser())->parse($yamlDefinitions);

        $fixtureDefinition = null;
        $fixtureType = null;

        if (array_key_exists($fixtureName, $definitions)) {
            $fixtureDefinition = $definitions[$fixtureName];
            $fixtureType = "array";
        } else {
            foreach ($definitions as $definitionName => $definitionData) {
                if (preg_match("/^" . $fixtureName . "<(.*)>$/", $definitionName, $matches)) {
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

        return $this->convertDefinition($fixtureDefinition, $fixtureType, $namespace);
    }

    /**
     * @param mixed $fixtureDefinition
     * @param string $type
     * @param string $namespace
     * @return array
     */
    private function convertDefinition($fixtureDefinition, $type, $namespace)
    {
        if (!is_array($fixtureDefinition)) {
            $isReference = substr($fixtureDefinition, 0, 1) === "@";

            if ($isReference) {
                $fixtureName = substr($fixtureDefinition, 1);
                return $this->fixture("{$namespace}.{$fixtureName}");
            }

            return $fixtureDefinition;
        }

        $values = [];

        foreach ($fixtureDefinition as $key => $value) {
            if (preg_match("/^(.*)<(.*)>$/", $key, $matches)) {
                $values[$matches[1]] = $this->convertDefinition($value, $matches[2], $namespace);
            } else {
                $values[$key] = $this->convertDefinition($value, "array", $namespace);
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
