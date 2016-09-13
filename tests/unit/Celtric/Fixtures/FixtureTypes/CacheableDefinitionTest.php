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
        $definition = new CacheableDefinition(new ScalarFixture("Ricard"), true);

        $this->assertEquals("Ricard", $definition->instantiate());
    }

    /** @test */
    public function only_calls_wrapped_definition_once()
    {
        $spy = $this->prophesize(FixtureDefinition::class);
        $spy->instantiate()->willReturn("Ricard");
        $definition = new CacheableDefinition($spy->reveal(), true);

        $definition->instantiate();
        $definition->instantiate();
        $definition->instantiate();

        $spy->instantiate()->shouldHaveBeenCalledTimes(1);
    }

    /** @test */
    public function can_clone_objects()
    {
        $definition = new CacheableDefinition(new ObjectFixture(Person::class, []), true);

        $definition->instantiate()->setFriend(new Person("A friend", 30));

        $this->assertNull($definition->instantiate()->friend());
    }

    /** @test */
    public function can_ignore_cloning()
    {
        $definition = new CacheableDefinition(new ObjectFixture(Person::class, []), false);

        $definition->instantiate()->setFriend(new Person("A friend", 30));

        $this->assertNotEmpty($definition->instantiate()->friend());
    }
}
