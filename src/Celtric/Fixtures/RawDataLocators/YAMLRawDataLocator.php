<?php

namespace Celtric\Fixtures\RawDataLocators;

use Celtric\Fixtures\RawDataLocator;
use Celtric\Fixtures\FixtureIdentifier;
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
    public function locate(FixtureIdentifier $fixtureIdentifier)
    {
        $fileContent = file_get_contents($this->toFilePath($fixtureIdentifier));

        return (new Parser())->parse($fileContent);
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
