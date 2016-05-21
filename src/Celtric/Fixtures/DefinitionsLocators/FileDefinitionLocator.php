<?php

namespace Celtric\Fixtures\DefinitionsLocators;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\FixtureDefinition;
use Celtric\Fixtures\FixtureIdentifier;
use Symfony\Component\Yaml\Parser;

final class FileDefinitionLocator implements DefinitionLocator
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
     * @inheritDoc
     */
    public function locate(FixtureIdentifier $fixtureIdentifier)
    {
        $yamlDefinitions = file_get_contents($this->toFilePath($fixtureIdentifier));

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

        if (array_key_exists($fixtureIdentifier->name(), $definitions)) {
            $fixtureDefinition = $definitions[$fixtureIdentifier->name()];
            $fixtureType = $rootType;
        } else {
            foreach ($definitions as $definitionName => $definitionData) {
                if (preg_match("/^{$fixtureIdentifier->name()}<(.*)>$/", $definitionName, $matches)) {
                    $fixtureDefinition = $definitions[$matches[0]];
                    $fixtureType = $matches[1];
                    break;
                }
            }
        }

        if (empty($fixtureType)) {
            throw new \RuntimeException("Could not find fixture \"{$fixtureIdentifier->toString()}\"");
        }

        if ($fixtureType !== "array" && !class_exists($fixtureType)) {
            throw new \RuntimeException("Could not find type \"{$fixtureType}\"");
        }

        if ($fixtureType === "array" && empty($fixtureDefinition)) {
            $fixtureDefinition = [];
        }

        return new FixtureDefinition($fixtureType, $fixtureDefinition);
    }

    /**
     * @param FixtureIdentifier $fixtureIdentifier
     * @return string
     */
    private function toFilePath(FixtureIdentifier $fixtureIdentifier)
    {
        return $this->rootPath . str_replace(".", DIRECTORY_SEPARATOR, $fixtureIdentifier->getNamespace()) . ".yml";
    }
}
