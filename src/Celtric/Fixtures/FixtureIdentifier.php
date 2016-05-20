<?php

namespace Celtric\Fixtures;

final class FixtureIdentifier
{
    /** @var string */
    private $namespace;

    /** @var string */
    private $fixtureName;

    /**
     * @param string $namespace
     * @param string $fixtureName
     */
    public function __construct($namespace, $fixtureName)
    {
        $this->namespace = $namespace;
        $this->fixtureName = $fixtureName;
    }

    /**
     * @param string $fullFixtureName
     * @return FixtureIdentifier
     */
    public static function fromFullFixtureName($fullFixtureName)
    {
        $fixtureName = array_reverse(explode(".", $fullFixtureName))[0];
        $namespace = substr($fullFixtureName, 0, strlen($fullFixtureName) - strlen($fixtureName) - 1);

        return new self($namespace, $fixtureName);
    }

    /**
     * @return string
     */
    public function fixtureName()
    {
        return $this->fixtureName;
    }

    /**
     * @param string $rootPath
     * @return string
     */
    public function toFilePath($rootPath)
    {
        return $rootPath . str_replace(".", DIRECTORY_SEPARATOR, $this->namespace) . ".yml";
    }
}
