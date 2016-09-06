<?php

namespace Tests\Unit\Celtric\Fixtures\FixtureTypes;

use Celtric\Fixtures\FixtureDefinition;
use Celtric\Fixtures\FixtureTypes\CacheableDefinition;
use Celtric\Fixtures\FixtureTypes\ObjectFixture;
use Celtric\Fixtures\FixtureTypes\ScalarFixture;
use Tests\Utils\Person;

final class CacheableDefinitionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function returns_wrapped_definition_value()
    {
        $definition = new CacheableDefinition(new ScalarFixture("Ricard"));

        $this->assertEquals("Ricard", $definition->instantiate());
    }

    /** @test */
    public function only_calls_definition_once()
    {
        $spy = $this->prophesize(FixtureDefinition::class);
        $spy->instantiate()->willReturn("Ricard");
        $definition = new CacheableDefinition($spy->reveal());

        $definition->instantiate();
        $definition->instantiate();
        $definition->instantiate();

        $spy->instantiate()->shouldHaveBeenCalledTimes(1);
    }

    /** @test */
    public function clones_objects()
    {
        $definition = new CacheableDefinition(new ObjectFixture(Person::class, []));

        $definition->instantiate()->setFriend(new Person("A friend", 30));

        $this->assertNull($definition->instantiate()->friend());
    }
}
