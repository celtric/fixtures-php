<?php

namespace Tests\Unit\Celtric\Fixtures\DefinitionLocators;

use Celtric\Fixtures\DefinitionLocator;
use Celtric\Fixtures\DefinitionLocators\LocatorCacher;
use Celtric\Fixtures\FixtureIdentifier;

final class LocatorCacherTest extends \PHPUnit_Framework_TestCase
{
    /** @var mixed */
    private $wrappedLocator;

    /** @test */
    public function returns_wrapped_locator_fixture()
    {
        $this->wrappedLocator->fixtureDefinition($this->identifier("foo"))->willReturn("bar");

        $this->assertEquals("bar", $this->cacher()->fixtureDefinition($this->identifier("foo")));
    }

    /** @test */
    public function only_calls_wrapped_locator_once_when_retrieving_fixture()
    {
        $this->wrappedLocator->fixtureDefinition($this->identifier("foo"))->willReturn("bar");

        $cacher = $this->cacher();
        $cacher->fixtureDefinition($this->identifier("foo"));
        $cacher->fixtureDefinition($this->identifier("foo"));
        $cacher->fixtureDefinition($this->identifier("foo"));

        $this->wrappedLocator->fixtureDefinition($this->identifier("foo"))->shouldHaveBeenCalledTimes(1);
    }

    /** @test */
    public function returns_wrapped_locator_namespace()
    {
        $this->wrappedLocator->namespaceDefinitions("foo")->willReturn("bar");

        $this->assertEquals("bar", $this->cacher()->namespaceDefinitions("foo"));
    }

    /** @test */
    public function only_calls_wrapped_locator_once_when_retrieving_namespace()
    {
        $this->wrappedLocator->namespaceDefinitions("foo")->willReturn("bar");

        $cacher = $this->cacher();
        $cacher->namespaceDefinitions("foo");
        $cacher->namespaceDefinitions("foo");
        $cacher->namespaceDefinitions("foo");

        $this->wrappedLocator->namespaceDefinitions("foo")->shouldHaveBeenCalledTimes(1);
    }

    //---[ Helpers ]--------------------------------------------------------------------//
    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->wrappedLocator = $this->prophesize(DefinitionLocator::class);
    }

    /**
     * @return LocatorCacher
     */
    private function cacher()
    {
        return new LocatorCacher($this->wrappedLocator->reveal());
    }

    /**
     * @param string $name
     * @return FixtureIdentifier
     */
    private function identifier($name)
    {
        return new FixtureIdentifier($name);
    }
}
