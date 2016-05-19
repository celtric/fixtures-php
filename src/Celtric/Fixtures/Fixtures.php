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
        list($filePathDefinition, $fixtureName) = explode(".", $fullFixtureName);

        $yamlDefinitions = file_get_contents(
                $this->rootPath . str_replace(".", DIRECTORY_SEPARATOR, $filePathDefinition) . ".yml");
        
        $definitions = (new Parser())->parse($yamlDefinitions);

        return $definitions[$fixtureName] ?: [];
    }
}
