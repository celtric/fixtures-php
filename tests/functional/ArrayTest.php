<?php

namespace Tests\Functional;

use Celtric\Fixtures\Fixtures;

final class ArrayTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function empty_array()
    {
        $this->assertEquals([], $this->fixture("arrays.empty"));
    }

    /** @test */
    public function scalar_values()
    {
        $this->assertEquals([
            "int" => 123,
            "float" => 123.456,
            "string" => "Foo",
            "bool" => true
        ], $this->fixture("arrays.scalar_values"));
    }

    /** @test */
    public function multidimensional()
    {
        $this->assertEquals([
            "foo" => "bar",
            "one" => [
                "two" => [
                    "three" => "foobar"
                ]
            ]
        ], $this->fixture("arrays.multidimensional"));
    }

    /** @test */
    public function typed_array()
    {
        $this->assertEquals([
            "foo" => "bar"
        ], $this->fixture("arrays.typed_array"));
    }

    /** @test */
    public function multidimensional_typed_array()
    {
        $this->assertEquals([
            "foo" => "bar",
            "one" => [
                "two" => [
                    "three" => "foobar"
                ]
            ]
        ], $this->fixture("arrays.multidimensional_typed_array"));
    }

    //---[ Helpers ]--------------------------------------------------------------------//

    /**
     * @param string $fixtureIdentifier
     * @return mixed
     */
    private function fixture($fixtureIdentifier)
    {
        return (new Fixtures(__DIR__ . "/../fixtures/"))->fixture($fixtureIdentifier);
    }
}
