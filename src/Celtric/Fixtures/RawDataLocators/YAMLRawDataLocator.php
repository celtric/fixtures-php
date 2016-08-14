<?php

namespace Celtric\Fixtures\RawDataLocators;

use Celtric\Fixtures\RawDataLocator;
use Symfony\Component\Yaml\Parser;

final class YAMLRawDataLocator implements RawDataLocator
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
     * @inheritDoc
     */
    public function retrieveRawData($namespace)
    {
        $fileContent = file_get_contents($this->toFilePath($namespace));

        return (new Parser())->parse($fileContent);
    }

    /**
     * @param string $namespace
     * @return string
     */
    private function toFilePath($namespace)
    {
        return $this->rootPath . str_replace(".", DIRECTORY_SEPARATOR, $namespace) . ".yml";
    }
}
